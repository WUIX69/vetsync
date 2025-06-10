<?php

include '../../../core/app.php';
// apiHeaders();

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
            if (isset($_GET['folder'])) {
                $foldername = $_GET['folder'];
                $pond = $filepond->loadWherePermanent($foldername, $reference_model);
                error_log("filepond load: " . json_encode($pond));
                $filepath = $pond['filepath'];
                if (file_exists($filepath)) {
                    // Expose Content-Disposition for CORS if needed
                    header('Access-Control-Expose-Headers: Content-Disposition');
                    header('Content-Type: ' . mime_content_type($filepath));
                    header('Content-Length: ' . filesize($filepath));
                    header('Content-Disposition: inline; filename="' . basename($filepath) . '"');
                    readfile($filepath);
                    exit;
                } else {
                    http_response_code(404);
                    echo 'File not found';
                    exit;
                }
            } else {
                $product_uuid = $_GET['product_uuid'] ?? null;
                $response = Attachments::all($product_uuid);

                $files = [];
                if (!empty($response['data'])) {
                    foreach ($response['data'] as $file) {
                        $files[] = [
                            'source' => $file['folder'],
                            'options' => [
                                'type' => 'local',
                                'file' => [
                                    'name' => $file['filename'],
                                ],
                            ],
                            'metadata' => [
                                'serverId' => $file['folder'],
                            ],
                        ];
                    }
                }
                $response['data'] = $files;
            }
            break;

        case 'DELETE':
            $foldername = file_get_contents('php://input');
            // error_log("foldername: " . $foldername);
            $response = $filepond->deleteWhereTemporary($foldername);
            $response = $filepond->deleteWherePermanent($foldername, $reference_model);
            break;
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;