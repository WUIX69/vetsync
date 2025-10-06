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

        // AUTO HEALTH RECOVERY: Restore health on login (if eligible)
        global $conn;

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
