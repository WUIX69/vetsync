<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Pets;
use VetSync\Models\Categories;
use VetSync\Models\Attachments;

use VetSync\Utils\Php\Helpers;
use VetSync\Utils\Php\Formatters;

use VetSync\Services\FileManager;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

try {

    $fileManager = new FileManager();
    $reference_model = 'pets';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? null;

        $data = [
            'user_uuid' => userData()['uuid'] ?? null, // Use server-side session
            'name' => $_POST['name'] ?? '',
            'dob' => $_POST['dob'] ?? '',
            'species' => $_POST['species'] ?? '',
            'breed' => $_POST['breed'] ?? '',
            'files' => $_POST['files'] ? explode(',', $_POST['files']) : [],
        ];

        if ($action === 'store') {
            $data['uuid'] = uuid();
            $response = Pets::store($data);

            // Handle FilePond files (same pattern as services)
            foreach ($data['files'] as $file) {
                $fileManager->storeWherePermanent($file, $reference_model, $data['uuid']);
            }

        } else if ($action === 'update') {
            $data['uuid'] = $_POST['uuid'] ?? null; // add pet uuid to data, required for update
            $response = Pets::update($data);

            // Handle FilePond files (same pattern as services)
            foreach ($data['files'] as $file) {
                $fileManager->storeWherePermanent($file, $reference_model, $data['uuid']);
            }

        } else {
            $response['message'] = 'Invalid POST action';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $action = $_GET['action'] ?? null;

        if ($action === 'all') {
            $user_uuid = userData()['uuid'] ?? null;
            error_log("User UUID for pets: " . $user_uuid);
            $result = Pets::all($user_uuid); // Pass user_uuid to the model
            $result['data'] = array_map(function ($item) use ($reference_model) {
                // Format correct data
                $formattedData = [
                    'image' => media($item['uuid']),
                    'created_at' => Formatters::dateToMDY($item['created_at']),
                ];

                // Remove unnecessary data
                unset($item['category_id'], $item['faqs'], $item['etd']);

                // Merge formatted data with the original item
                return array_merge($item, $formattedData);
            }, $result['data'] ?? []);
            $response = $result;

        } else if ($action === 'single') {
            $pet_uuid = $_GET['uuid'] ?? null;
            $response = Pets::single($pet_uuid);

            // Add formatted image and files data (same as the 'all' action)
            if ($response['success'] && !empty($response['data'])) {
                $response['data']['image'] = media($response['data']['uuid']);
                $response['data']['files'] = Attachments::all($pet_uuid)['data'] ?? [];
                $response['data']['created_at'] = Formatters::dateToMDY($response['data']['created_at']);
            }
        } else {
            $response['message'] = 'Invalid GET action';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $pet_uuid = $_GET['uuid'] ?? null;
        $response = Pets::delete($pet_uuid);
        if ($response['success']) {
            $fileManager->deleteWhereReferencePermanent($pet_uuid, $reference_model);
        }
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;