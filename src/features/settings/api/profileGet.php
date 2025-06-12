<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Users;
use VetSync\Models\Attachments;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response['message'] = 'Invalid request GET method';
    echo json_encode($response);
    exit;
}

try {

    $user_uuid = userData()['uuid'];
    $user = Users::single($user_uuid) ?? [];

    $userData = [
        'firstname' => $user['firstname'],
        'lastname' => $user['lastname'],
        'email' => $user['email'],
        'bio' => $user['bio'],
        'telephone' => $user['telephone'],
        'dob' => $user['dob'],
        'location' => $user['location'],
        'urls' => $user['urls'] ? explode(',', $user['urls']) : [], // Format the urls array
        'profile' => Attachments::single($user_uuid)['data'],
    ];

    $response = array_merge($response, [
        'success' => true,
        'message' => 'Profile data fetched successfully',
        'data' => $userData,
    ]);

} catch (Exception $e) {
    error_log($e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;