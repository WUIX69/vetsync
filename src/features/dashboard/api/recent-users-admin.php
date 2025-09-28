<?php

include '../../../core/app.php';
apiHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Get recent users (last 4 only)
    $stmt = $conn->prepare("
        SELECT 
            uuid,
            firstname,
            lastname,
            email,
            created_at,
            CONCAT(firstname, ' ', lastname) as full_name
        FROM users 
        ORDER BY created_at DESC 
        LIMIT 4
    ");

    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the users
    $formattedUsers = [];
    foreach ($users as $user) {
        // Calculate time ago
        $timeAgo = getTimeAgo($user['created_at']);

        $formattedUsers[] = [
            'uuid' => $user['uuid'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'created_at' => $user['created_at'],
            'time_ago' => $timeAgo,
            'avatar_url' => media($user['uuid']) // Get profile image
        ];
    }

    $response = [
        'success' => true,
        'data' => $formattedUsers
    ];

} catch (PDOException $e) {
    error_log("Recent Users Error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'Database error occurred'
    ];
} catch (Exception $e) {
    error_log("Recent Users General Error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'An error occurred while fetching users'
    ];
}

function getTimeAgo($datetime)
{
    $time = time() - strtotime($datetime);

    if ($time < 60)
        return 'Just now';
    if ($time < 3600)
        return floor($time / 60) . ' mins ago';
    if ($time < 86400)
        return floor($time / 3600) . ' hours ago';
    if ($time < 2592000)
        return floor($time / 86400) . ' days ago';
    if ($time < 31536000)
        return floor($time / 2592000) . ' months ago';
    return floor($time / 31536000) . ' years ago';
}

echo json_encode($response);
exit;
?>