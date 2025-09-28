<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Users;
use VetSync\Services\FileManager;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method";
    exit;
}

try {
    $user_uuid = userData()['uuid'] ?? null;
    $action = $_POST['action'] ?? null;

    // Log the incoming request for debugging
    error_log("Profile Request - User UUID: " . $user_uuid . ", Action: " . $action);
    error_log("FILES received: " . print_r($_FILES, true));

    if ($action === 'profile-upload') {

        // Check for FilePond file upload (FilePond sends files as 'filepond' by default)
        $fileKey = null;
        if (isset($_FILES['filepond']) && $_FILES['filepond']['error'] === UPLOAD_ERR_OK) {
            $fileKey = 'filepond';
        } elseif (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
            $fileKey = 'profile';
        }

        if (!$fileKey) {
            error_log("No valid file upload found");
            echo "No file uploaded";
            exit;
        }

        error_log("Using file key: " . $fileKey);

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = $_FILES[$fileKey]['type'];
        if (!in_array($fileType, $allowedTypes)) {
            error_log("Invalid file type: " . $fileType);
            echo "Invalid file type";
            exit;
        }

        // Validate file size (4MB max) - Changed from 2MB to 4MB
        if ($_FILES[$fileKey]['size'] > 4 * 1024 * 1024) {
            error_log("File too large: " . $_FILES[$fileKey]['size'] . " bytes (max: 4MB)");
            echo "File too large - maximum size is 4MB";
            exit;
        }

        // Upload the file
        $fileManager = new FileManager();
        $uploadResponse = $fileManager->storeWhereInstant($_FILES[$fileKey], 'profiles', $user_uuid);

        error_log("Upload response: " . print_r($uploadResponse, true));

        if ($uploadResponse['success']) {
            // FilePond expects just the folder name as plain text for successful uploads
            echo $uploadResponse['data']['folder'];
            exit;
        } else {
            error_log("Upload failed: " . $uploadResponse['message']);
            echo "Upload failed: " . $uploadResponse['message'];
            exit;
        }

    } else if ($action === 'profile-update') {

        // Handle regular profile updates
        $required_fields = ['firstname', 'lastname', 'telephone', 'location'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                $response = [
                    'success' => false,
                    'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required.'
                ];
                echo json_encode($response);
                exit;
            }
        }

        $data = [
            'user_uuid' => $user_uuid,
            'firstname' => trim($_POST['firstname']),
            'lastname' => trim($_POST['lastname']),
            'telephone' => trim($_POST['telephone']),
            'location' => trim($_POST['location']),
        ];

        $response = Users::updateProfile($data);
        echo json_encode($response);
        exit;

    } else {
        echo "Invalid action: " . $action;
        exit;
    }

} catch (Exception $e) {
    error_log("Profile Upload/Update Error: " . $e->getMessage());
    echo "Server error occurred";
    exit;
}
?>