<?php

include '../../../../core/app.php';
featured('auth/login/db/users');
apiHeaders();

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

    $users = new UsersDB();
    $user = $users->singleWhereUserEmail($email);
    $admin = $users->singleWhereAdminEmail($email);

    if ($user && password_verify($password, $user['password'])) {

        $session->set($user);
        $session->add(['type' => 'user']);

        $response = array_merge($response, [
            'success' => true,
            'message' => 'Welcome user',
            'route' => app('user'),
        ]);

    } else if ($admin && password_verify($password, $admin['password'])) {

        $session->set($admin);
        $session->add(['type' => 'admin']);

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
