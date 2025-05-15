<?php

include '../../../../core/app.php';
featured('auth/login/db/users');
apiHeaders();

$response = [
    'success' => false,
    'message' => '',
    'route' => null
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

try {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = singleWhereUserEmail($email);
    $admin = singleWhereAdminEmail($email);

    if ($user && password_verify($password, $user['password'])) {

        sessionSet($user);
        sessionAdd(['type' => 'user']);
        $response = array_merge($response, [
            'success' => true,
            'message' => 'Welcome user',
            'route' => app('user'),
        ]);

    } else if ($admin && password_verify($password, $admin['password'])) {

        sessionSet($admin);
        sessionAdd(['type' => 'admin']);
        $response = array_merge($response, [
            'success' => true,
            'message' => 'Welcome admin',
            'route' => app('admin'),
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
