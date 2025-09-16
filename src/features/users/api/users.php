<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Users;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

try {
    // Add debugging
    error_log("Users API called with action: " . ($_POST['action'] ?? $_GET['action'] ?? 'none'));
    error_log("POST data: " . print_r($_POST, true));

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $action = $_GET['action'] ?? null;

        if ($action === 'singleWhereView' || $action === 'singleWhereEdit') {
            $user_uuid = $_GET['uuid'] ?? null;
            if (!$user_uuid) {
                $response = [
                    'success' => false,
                    'message' => 'User UUID is required'
                ];
            } else {
                $response = Users::single($user_uuid);
            }

            echo json_encode($response);
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? null;

        // Handle verification status updates
        if ($action === 'update_verification') {
            $user_uuid = $_POST['user_uuid'] ?? null;
            $status = $_POST['status'] ?? null;
            $user_data = userData();
            $verified_by = 'Admin';

            // Try to get admin name safely
            if (isset($user_data['name'])) {
                $verified_by = $user_data['name']; // For admin login (has 'name' field)
            } elseif (isset($user_data['firstname']) && isset($user_data['lastname'])) {
                $verified_by = $user_data['firstname'] . ' ' . $user_data['lastname']; // For user login
            }

            if (!$user_uuid || !$status) {
                $response = [
                    'success' => false,
                    'message' => 'Missing user UUID or status'
                ];
            } else {
                $response = Users::updateVerificationStatus($user_uuid, $status, $verified_by);
            }

            echo json_encode($response);
            exit;
        }

        // Existing store/update logic
        $data = [
            'firstname' => $_POST['firstname'] ?? '',
            'lastname' => $_POST['lastname'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telephone' => $_POST['telephone'] ? $_POST['telephone'] : null,
            'dob' => $_POST['dob'] ? $_POST['dob'] : null,
            'role' => $_POST['role'] ? $_POST['role'] : null,
        ];

        if ($action === 'store') {
            $data['uuid'] = uuid();
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $response = Users::storeWhereUserAdmin($data) ?? [];
        } else if ($action === 'update') {
            $data['uuid'] = $_POST['uuid'] ?? null;
            $response = Users::updateWhereUserAdmin($data) ?? [];
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $user_uuid = $_GET['user_uuid'] ?? null;
        $response = Users::delete($user_uuid) ?? [];
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;