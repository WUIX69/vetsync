<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Model\Users;
use VetSync\Model\Attachments;
use VetSync\Service\FilePond;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

function urlsHandler($urls = null)
{
    if (is_array($urls) && !empty($urls)) {
        // Remove empty values
        $filtered = array_filter($urls, function ($url) {
            return trim($url) !== '';
        });
        // Only implode if there are any non-empty values left
        return !empty($filtered) ? implode(',', $filtered) : null;
    }

    return null;
}

try {

    $user_uuid = userData()['uuid'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($action === 'profile-upload') {

        $filePond = new FilePond();
        $attachments = new Attachments();
        $reference_model = 'profiles';

        $profile = $filePond->store($_FILES['profile'], $reference_model);
        $data = [
            'reference_model' => $reference_model,
            'reference_uuid' => $user_uuid,
            'folder' => $profile['folder'],
            'filename' => $profile['filename'],
        ];

        // Delete Existing files first
        $oldProfile = $attachments->single($user_uuid);
        if ($oldProfile) {
            $filePond->delete($reference_model, $oldProfile['folder']);
            $attachments->delete($reference_model, $user_uuid);
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
            'urls' => urlsHandler($_POST['urls']),
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
