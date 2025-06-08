<?php

include '../../../core/app.php';
apiHeaders();

// You might need to set appropriate headers for CORS if your frontend is on a different domain
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-File-Id, X-Requested-With'); // X-File-Id is crucial for revert

use VetSync\Services\FilePond;
use VetSync\Models\Attachments;

try {

    $filepond = new FilePond();
    $attachments = new Attachments();

    $reference_model = 'products';
    $foldername = isset($_SERVER['HTTP_X_FILE_ID']) ? $_SERVER['HTTP_X_FILE_ID'] : ($_GET['foldername'] ?? null);
    // error_log("foldername: " . $foldername);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? null;

        if ($action === 'process') {
            $response = $filepond->storeWhereTemporary($_FILES['image']);
        } else {
            $response['message'] = 'Invalid FilePond POST action';
        }

    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $product_uuid = $_GET['product_uuid'] ?? null;
        $response = $attachments->all($product_uuid);

        if ($response['success']) {
            $files = [];
            foreach ($response['data'] as $file) {
                $pond = $filepond->load($file['folder'], $reference_model);
                $files[] = [
                    'source' => $pond['path'],
                    'options' => [
                        'type' => 'local',
                        'file' => [
                            'name' => $pond['name'],
                            'size' => $pond['size'],
                            'type' => $pond['type'],
                            'path' => $pond['path'],
                            'folder' => $pond['folder'],
                        ],
                    ],
                    'metadata' => [
                        'serverId' => $pond['path'],
                    ],
                ];
            }
            $response['files'] = $files;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $foldername = $foldername ?? file_get_contents('php://input');
        $response = $filepond->deleteWhereTemporary($foldername);
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;