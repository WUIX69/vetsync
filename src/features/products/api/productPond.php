<?php

include '../../../core/app.php';
// apiHeaders();

use VetSync\Services\FilePond;

try {

    $filepond = new FilePond();
    $reference_model = 'products';

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            echo $filepond->storeWhereTemporary($_FILES['file']);
            break;

        case 'GET':
            $foldername = $_GET['folder'] ?? null;
            $file = $filepond->loadWherePermanent($foldername, $reference_model);
            if (file_exists($file['filepath'])) {
                header('Access-Control-Expose-Headers: Content-Disposition'); // Expose Content-Disposition for CORS if needed
                header('Content-Type: ' . $file['mimetype']);
                header('Content-Length: ' . $file['filesize']);
                header('Content-Disposition: inline; filename="' . $file['filename'] . '"');
                readfile($file['filepath']);
            }
            break;

        case 'DELETE':
            $foldername = file_get_contents('php://input');
            // error_log("foldername: " . $foldername);
            $filepond->deleteWhereTemporary($foldername);
            $filepond->deleteWherePermanent($foldername, $reference_model);
            break;
    }

} catch (Exception $e) {
    error_log($e->getMessage());
}

exit;