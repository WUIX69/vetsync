<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Model\Users;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response['message'] = 'Invalid request GET method';
    echo json_encode($response);
    exit;
}

$user_uuid = userData()['uuid'];
// $action = $_GET['action'];

try {

    $user = new Users();
    $data = $user->single($user_uuid) ?? [];

    // Fetch only the needed data
    $userData = [
        'firstname' => $data['firstname'],
        'lastname' => $data['lastname'],
        'email' => $data['email'],
        'bio' => $data['bio'],
        'telephone' => $data['telephone'],
        'dob' => $data['dob'],
        'location' => $data['location'],
        'urls' => $data['urls'] ? explode(',', $data['urls']) : [], // Format the urls array
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