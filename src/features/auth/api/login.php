<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Users;
// global $response;
// global $session;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

try {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = Users::singleWhereUserEmail($email);
    $admin = Users::singleWhereAdminEmail($email);

    if ($user && password_verify($password, $user['password'])) {

        // AUTO NO-SHOW DETECTION: Check for past accepted appointments
        global $conn;

        // Find accepted appointments that are past their scheduled date
        $findNoShows = $conn->prepare('
            SELECT uuid, pet_uuid
            FROM appointments 
            WHERE user_uuid = ? 
            AND status = "accepted" 
            AND date < CURDATE()
        ');
        $findNoShows->execute([$user['uuid']]);
        $noShowAppointments = $findNoShows->fetchAll(PDO::FETCH_ASSOC);

        // Mark each as NO SHOW and penalize health
        if (count($noShowAppointments) > 0) {
            $markNoShow = $conn->prepare('
                UPDATE appointments 
                SET status = "cancelled", 
                    cancellation_reason = "NO SHOW - Did not attend scheduled appointment"
                WHERE uuid = ?
            ');

            foreach ($noShowAppointments as $appointment) {
                $markNoShow->execute([$appointment['uuid']]);
            }

            // Apply 20% health penalty for no-shows
            $penaltyAmount = count($noShowAppointments) * 20;
            $updateHealth = $conn->prepare('
                UPDATE users 
                SET health = GREATEST(0, health - ?)
                WHERE uuid = ?
            ');
            $updateHealth->execute([$penaltyAmount, $user['uuid']]);

            error_log("No-show penalty: {$email} lost {$penaltyAmount}% health for " . count($noShowAppointments) . " missed appointment(s)");
        }

        // AUTO HEALTH RECOVERY: Restore health on login (if eligible)
        // Check if user has recent no-shows (last 7 days)
        $checkNoShows = $conn->prepare('
            SELECT 
                (SELECT COUNT(*) FROM appointments 
                 WHERE user_uuid = ? 
                 AND status = "cancelled" 
                 AND (cancellation_reason LIKE "%NO SHOW%" OR cancellation_reason LIKE "%no show%")
                 AND updated_at > DATE_SUB(NOW(), INTERVAL 7 DAY)) +
                (SELECT COUNT(*) FROM reservations 
                 WHERE user_uuid = ? 
                 AND cancellation_reason LIKE "%NO SHOW%"
                 AND updated_at > DATE_SUB(NOW(), INTERVAL 7 DAY))
                as recent_no_shows
        ');
        $checkNoShows->execute([$user['uuid'], $user['uuid']]);
        $noShowData = $checkNoShows->fetch(PDO::FETCH_ASSOC);

        // Restore 2% health if no recent no-shows and health is below 100%
        if ($noShowData && $noShowData['recent_no_shows'] == 0 && $user['health'] < 100) {
            $updateHealth = $conn->prepare('
                UPDATE users 
                SET health = LEAST(100, health + 2)
                WHERE uuid = ?
            ');
            $updateHealth->execute([$user['uuid']]);

            error_log("Auto health recovery: {$email} gained +2% health on login (no recent no-shows)");
        }

        $session->set($user);
        $session->add(['type' => 'user']);
        session_write_close();

        $response = array_merge($response, [
            'success' => true,
            'message' => 'Welcome user',
            'data' => [
                'route' => app('user'),
            ],
        ]);

    } else if ($admin && password_verify($password, $admin['password'])) {

        $session->set($admin);
        $session->add(['type' => 'admin']);
        session_write_close();

        $response = array_merge($response, [
            'success' => true,
            'message' => 'Welcome admin',
            'data' => [
                'route' => app('admin'),
            ],
        ]);

    } else {
        $response['message'] = 'Invalid email and/or password!';
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
