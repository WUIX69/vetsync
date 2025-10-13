<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Users;
use VetSync\Models\Attachments;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response = [
        'success' => false,
        'message' => 'Invalid request method'
    ];
    echo json_encode($response);
    exit;
}

try {
    $user_uuid = userData()['uuid'];
    $userResponse = Users::single($user_uuid);
    $user = $userResponse['data'] ?? [];

    // Get profile image attachment
    $attachmentResponse = Attachments::single($user_uuid);
    $profileData = null;

    if ($attachmentResponse && $attachmentResponse['success'] && isset($attachmentResponse['data'])) {
        $profileData = $attachmentResponse['data'];
        // Add the full URL using media() helper
        $profileData['url'] = media($user_uuid);
    }

    // Format and Fetch only needed data
    $response = [
        'success' => true,
        'data' => [
            'firstname' => $user['firstname'] ?? '',
            'lastname' => $user['lastname'] ?? '',
            'email' => $user['email'] ?? '',
            'telephone' => $user['telephone'] ?? '',
            'location' => $user['location'] ?? '',
            'profile' => $profileData,
        ]
    ];

    // Debug logging
    error_log("Profile GET - User UUID: " . $user_uuid);
    error_log("Profile attachment data: " . print_r($profileData, true));

} catch (Exception $e) {
    error_log("Profile Get Error: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'Failed to load profile data.'
    ];
}

echo json_encode($response);
exit;