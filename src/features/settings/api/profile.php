<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Model\Users;
use VetSync\Services\Attachments;
use VetSync\Services\FilePond;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

try {

    $user_uuid = userData()['uuid'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($action === 'profile-upload') {

        $filePond = new FilePond();
        $attachments = new Attachments();

        $folder = $filePond->process($_FILES['profile']);
        $moved = $filePond->move($folder, 'profiles'); // Move folder/file to profiles folder directly for profile upload only

        $data = [
            'reference_model' => 'profiles',
            'reference_uuid' => $user_uuid,
            'folder' => $moved['folder'],
            'filename' => $moved['filename'],
        ];

        // Delete Existing files first
        $oldProfile = $attachments->single($user_uuid);
        if ($oldProfile) {
            $filePond->delete('profiles', $oldProfile['folder']);
            $attachments->delete('profiles', $user_uuid);
        }

        // Store new profile picture
        $response = $attachments->store($data);
        $response['data']['profile_url'] = userData()['profile'];

    } else if ($action === 'profile-update') {

        $data = [
            'user_uuid' => $user_uuid,
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'telephone' => $_POST['telephone'],
            'dob' => $_POST['dob'],
            'email' => $_POST['email'],
            'location' => $_POST['location'],
            'bio' => $_POST['bio'] ? $_POST['bio'] : null,
            'url' => $_POST['url'] ? $_POST['url'] : null,
        ];

        // Update profile
        $user = new Users();
        $response = $user->update($data);

    } else {
        $response['message'] = 'Invalid profile action';
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
