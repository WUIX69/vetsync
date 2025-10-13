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
        // Calculate time ago properly
        $timeAgo = getTimeAgo($user['created_at']);

        // Try to get actual profile image, fallback to placeholder
        $avatarUrl = null;
        if (function_exists('media')) {
            $avatarUrl = media($user['uuid']);
        }

        // If no profile image or media function doesn't exist, use a nice placeholder
        if (!$avatarUrl || $avatarUrl === '/public/img/profiles/') {
            $initial = strtoupper(substr($user['firstname'], 0, 1));
            $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($user['full_name']) .
                "&size=90&background=random&color=fff&font-size=0.6";
        }

        $formattedUsers[] = [
            'uuid' => $user['uuid'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'created_at' => $user['created_at'],
            'time_ago' => $timeAgo,
            'avatar_url' => $avatarUrl
        ];
    }

    $response = [
        'success' => true,
        'data' => $formattedUsers,
        'count' => count($formattedUsers)
    ];

} catch (PDOException $e) {
    error_log("Recent Users Error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'Database error occurred: ' . $e->getMessage()
    ];
} catch (Exception $e) {
    error_log("Recent Users General Error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'An error occurred while fetching users: ' . $e->getMessage()
    ];
}

function getTimeAgo($datetime)
{
    // Create DateTime objects
    $time = new DateTime($datetime);
    $now = new DateTime();

    // Calculate difference
    $diff = $now->diff($time);

    // Format the time ago string
    if ($diff->y > 0) {
        return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    } elseif ($diff->m > 0) {
        return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    } elseif ($diff->d > 0) {
        return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    } elseif ($diff->h > 0) {
        return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    } elseif ($diff->i > 0) {
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    } else {
        return 'Just now';
    }
}

echo json_encode($response);
exit;
?>