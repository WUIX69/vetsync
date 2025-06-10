<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Services\FilePond;
use VetSync\Models\Attachments;

try {

    $filepond = new FilePond();
    $reference_model = 'products';

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $response = $filepond->storeWhereTemporary($_FILES['file']);
            break;

        case 'GET':
            $product_uuid = $_GET['product_uuid'] ?? null;
            $response = Attachments::all($product_uuid);
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
            break;

        case 'DELETE':
            $foldername = file_get_contents('php://input');
            error_log("foldername: " . $foldername);
            $response = $filepond->deleteWhereTemporary($foldername);
            break;
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;