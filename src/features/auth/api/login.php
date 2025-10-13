<?php
// Clean any output buffering
while (ob_get_level()) {
    ob_end_clean();
}

include '../../../core/app.php';

// Set headers for JSON response
header('Content-Type: application/json');

use VetSync\Models\Users;

$response = [
    'success' => false,
    'message' => '',
];

try {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    error_log("=== LOGIN ATTEMPT START ===");
    error_log("Email: " . $email);
    error_log("Password length: " . strlen($password));
    error_log("Password: " . $password); // TEMP DEBUG - remove in production!

    if (!$email || !$password) {
        $response['message'] = 'All fields are required!';
        echo json_encode($response);
        exit;
    }

    $user = Users::singleWhereUserEmail($email);
    $admin = Users::singleWhereAdminEmail($email);

    error_log("User found: " . (!empty($user) ? 'YES' : 'NO'));
    error_log("Admin found: " . (!empty($admin) ? 'YES' : 'NO'));

    if ($user) {
        error_log("User password hash: " . substr($user['password'], 0, 30) . "...");
        $passwordMatch = password_verify($password, $user['password']);
        error_log("Password verify result: " . ($passwordMatch ? 'MATCH' : 'NO MATCH'));
    }

    if ($user && password_verify($password, $user['password'])) {
        error_log("LOGIN SUCCESS - User authenticated");

        // AUTO NO-SHOW DETECTION: Check for past accepted appointments
        global $conn;
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
                    note = CONCAT(IFNULL(note, ""), "[CANCELLED BY ADMIN] NO SHOW - Did not attend scheduled appointment")
                WHERE uuid = ?
            ');

            foreach ($noShowAppointments as $appointment) {
                $markNoShow->execute([$appointment['uuid']]);
            }

            // Apply 20% health penalty for no-shows
            $penaltyAmount = count($noShowAppointments) * 20;
            $updateHealth = $conn->prepare('
                UPDATE users 
                SET user_health = GREATEST(0, user_health - ?)
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
                 AND (note LIKE "%NO SHOW%" OR note LIKE "%no show%")
                 AND updated_at > DATE_SUB(NOW(), INTERVAL 7 DAY)) +
                (SELECT COUNT(*) FROM reservations 
                 WHERE user_uuid = ? 
                 AND rejection_reason LIKE "%NO SHOW%"
                 AND updated_at > DATE_SUB(NOW(), INTERVAL 7 DAY))
                as recent_no_shows
        ');
        $checkNoShows->execute([$user['uuid'], $user['uuid']]);
        $noShowData = $checkNoShows->fetch(PDO::FETCH_ASSOC);

        // Restore 2% health if no recent no-shows and health is below 100%
        if ($noShowData && $noShowData['recent_no_shows'] == 0 && $user['user_health'] < 100) {
            $updateHealth = $conn->prepare('
                UPDATE users 
                SET user_health = LEAST(100, user_health + 2)
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
