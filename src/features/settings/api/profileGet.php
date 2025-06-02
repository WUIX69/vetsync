<?php

include '../../../core/app.php';
apiHeaders();

// use VetSync\Model\Users;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response['message'] = 'Invalid request GET method';
    echo json_encode($response);
    exit;
}

// $user_uuid = userData()['uuid'];
// $action = $_GET['action'];

try {

    // $user = new Users();
    $data = userData();
    $data['urls'] = explode(',', $data['urls']); // Format the urls array

    // Remove unwanted fields, security purposes
    unset($data['uuid']);
    unset($data['created_at']);
    unset($data['is_dark']);
    unset($data['type']);
    unset($data['password']);
    unset($data['profile']);
    unset($data['name']);

    $response = array_merge($response, [
        'success' => true,
        'message' => 'Profile data fetched successfully',
        'data' => $data,
    ]);

} catch (Exception $e) {
    error_log($e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;