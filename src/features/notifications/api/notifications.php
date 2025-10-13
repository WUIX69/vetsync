<?php

include '../../../core/app.php';
apiHeaders();

global $conn, $session;

// Ensure user is logged in
if (!$session->has()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userData = $session->get();
$userUuid = $userData['uuid'];

$response = [];

// GET - Fetch notifications
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->prepare("
            SELECT * FROM notifications 
            WHERE user_uuid = ? 
            ORDER BY created_at DESC 
            LIMIT 50
        ");
        $stmt->execute([$userUuid]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            'success' => true,
            'data' => $notifications,
            'unread_count' => count(array_filter($notifications, fn($n) => $n['is_read'] == 0))
        ];
    } catch (PDOException $e) {
        $response = [
            'success' => false,
            'message' => 'Failed to fetch notifications: ' . $e->getMessage()
        ];
    }
}

// POST - Actions (mark_read, mark_all_read, delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'mark_read') {
            $notificationId = $_POST['id'] ?? null;

            if (!$notificationId) {
                throw new Exception('Notification ID is required');
            }

            $stmt = $conn->prepare("
                UPDATE notifications 
                SET is_read = 1 
                WHERE id = ? AND user_uuid = ?
            ");
            $stmt->execute([$notificationId, $userUuid]);

            $response = ['success' => true, 'message' => 'Notification marked as read'];

        } elseif ($action === 'mark_all_read') {
            $stmt = $conn->prepare("
                UPDATE notifications 
                SET is_read = 1 
                WHERE user_uuid = ? AND is_read = 0
            ");
            $stmt->execute([$userUuid]);

            $response = ['success' => true, 'message' => 'All notifications marked as read'];

        } elseif ($action === 'delete') {
            $notificationId = $_POST['id'] ?? null;

            if (!$notificationId) {
                throw new Exception('Notification ID is required');
            }

            $stmt = $conn->prepare("
                DELETE FROM notifications 
                WHERE id = ? AND user_uuid = ?
            ");
            $stmt->execute([$notificationId, $userUuid]);

            $response = ['success' => true, 'message' => 'Notification deleted'];

        } elseif ($action === 'delete_all') {
            $stmt = $conn->prepare("
                DELETE FROM notifications 
                WHERE user_uuid = ?
            ");
            $stmt->execute([$userUuid]);

            $response = ['success' => true, 'message' => 'All notifications deleted'];

        } else {
            throw new Exception('Invalid action');
        }

    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

echo json_encode($response);
exit;
