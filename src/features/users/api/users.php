<?php
// Clear any output buffers first
while (ob_get_level()) {
    ob_end_clean();
}

// Start fresh output buffering
ob_start();

// Set headers immediately
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Suppress warnings for clean JSON output
error_reporting(E_ERROR | E_PARSE);

// Include the core app - fix the path
include dirname(__FILE__) . '/../../../core/app.php';

use VetSync\Models\Users;
use Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? null;

        // Handle verification status updates
        if ($action === 'update_verification') {
            $user_uuid = $_POST['user_uuid'] ?? null;
            $status = $_POST['status'] ?? null;
            $rejection_reason = $_POST['rejection_reason'] ?? '';

            // Get admin info safely
            $verified_by = 'Admin';
            try {
                $user_data = userData();
                if (isset($user_data['name'])) {
                    $verified_by = $user_data['name'];
                } elseif (isset($user_data['firstname']) && isset($user_data['lastname'])) {
                    $verified_by = $user_data['firstname'] . ' ' . $user_data['lastname'];
                }
            } catch (Exception $e) {
                // Fallback to 'Admin' if userData() fails
                error_log("Failed to get user data: " . $e->getMessage());
            }

            if (!$user_uuid || !$status) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Missing user UUID or status'
                ]);
                exit;
            }

            // Call the verification update method
            $response = Users::updateVerificationStatus($user_uuid, $status, $verified_by);

            // Ensure response is an array
            if (!is_array($response)) {
                $response = [
                    'success' => false,
                    'message' => 'Invalid response from verification update'
                ];
            }

            // Send email notification after successful status update (optional)
            if ($response['success']) {
                try {
                    // Get user details for email
                    global $conn;
                    if (isset($conn)) {
                        $stmt = $conn->prepare("SELECT firstname, lastname, email FROM users WHERE uuid = ?");
                        $stmt->execute([$user_uuid]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($user && $user['email']) {
                            $emailService = new \VetSync\Services\Email();
                            $userName = trim($user['firstname'] . ' ' . $user['lastname']);

                            // Use the correct method names
                            if ($status === 'verified') {
                                $emailResult = $emailService->sendAccountValidated(
                                    $user['email'],
                                    $userName
                                );
                            } elseif ($status === 'rejected') {
                                $emailResult = $emailService->sendAccountRejected(
                                    $user['email'],
                                    $userName,
                                    $rejection_reason
                                );
                            }

                            // Log email result but don't fail if email fails
                            if (isset($emailResult) && !$emailResult['success']) {
                                error_log("Email notification failed: " . $emailResult['message']);
                            }
                        }
                    }
                } catch (Exception $e) {
                    error_log("Verification notification email error: " . $e->getMessage());
                    // Don't fail the verification update if email fails
                }
            }

            // Clean output buffer and send response
            ob_clean();
            echo json_encode($response);
            exit;
        }

        // Existing store/update logic
        $data = [
            'firstname' => $_POST['firstname'] ?? '',
            'lastname' => $_POST['lastname'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telephone' => $_POST['telephone'] ?? null,
            'role' => $_POST['role'] ?? null,
        ];

        if ($action === 'store') {
            $data['uuid'] = uuid();
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $response = Users::storeWhereUserAdmin($data) ?? [];

            // Send welcome email for new user registration
            if ($response['success'] && isset($data['email'])) {
                try {
                    $emailService = new \VetSync\Services\Email();
                    $userName = trim($data['firstname'] . ' ' . $data['lastname']);

                    $emailResult = $emailService->sendWelcomeEmail(
                        $data['email'],
                        $userName
                    );

                    if (!$emailResult['success']) {
                        error_log("Failed to send welcome email: " . $emailResult['message']);
                    }
                } catch (Exception $e) {
                    error_log("Welcome email error: " . $e->getMessage());
                }
            }
        } else if ($action === 'update') {
            $data['uuid'] = $_POST['uuid'] ?? null;
            $response = Users::updateWhereUserAdmin($data) ?? [];
        }

        ob_clean();
        echo json_encode($response);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $user_uuid = $_GET['user_uuid'] ?? null;

        if (!$user_uuid) {
            http_response_code(400);
            ob_clean();
            echo json_encode([
                'success' => false,
                'message' => 'Missing user UUID'
            ]);
            exit;
        }

        // ✅ FIXED: Use the correct method name 'delete' instead of 'deleteWhereUserAdmin'
        $response = Users::delete($user_uuid) ?? [];
        ob_clean();
        echo json_encode($response);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $action = $_GET['action'] ?? null;
        $uuid = $_GET['uuid'] ?? null;

        if (!$action || !$uuid) {
            http_response_code(400);
            ob_clean();
            echo json_encode([
                'success' => false,
                'message' => 'Missing action or UUID'
            ]);
            exit;
        }

        if ($action === 'singleWhereView' || $action === 'singleWhereEdit') {
            $response = Users::single($uuid) ?? [];
            ob_clean();
            echo json_encode($response);
            exit;
        }

        http_response_code(400);
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action'
        ]);
        exit;
    }

} catch (Exception $e) {
    error_log("Users API Error: " . $e->getMessage());
    http_response_code(500);
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error'
    ]);
    exit;
}
?>