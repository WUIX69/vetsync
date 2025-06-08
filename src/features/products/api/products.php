<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Products;
use VetSync\Models\Categories;

use VetSync\Utils\Php\Helpers;
use VetSync\Utils\Php\Formatters;

use VetSync\Services\FilePond;
use VetSync\Models\Attachments;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

function storePondHelper($filepond, $reference_model, $data, $attachments)
{
    global $response;

    if (!empty($data['image'])) {
        $result = $filepond->move($data['image'], $reference_model);
        $attachment_data = array_merge($result, [
            'reference_uuid' => $data['uuid'],
            'reference_model' => $reference_model,
        ]);

        $response = $attachments->store($attachment_data);
    }

    return $response;
}

try {

    $categories = new Categories();
    $products = new Products();
    $filepond = new FilePond();
    $attachments = new Attachments();
    $reference_model = 'products';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $attachments = new Attachments();
        $action = $_POST['action'] ?? null;

        $data = [
            'category_id' => $_POST['category_id'] ?? '',
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'og_price' => $_POST['og_price'] ?? '',
            'dc_price' => $_POST['dc_price'] ? $_POST['dc_price'] : null,
            'stock' => $_POST['stock'] ?? '',
            'status' => $_POST['status'] ?? '',
            'tags' => $_POST['tags'] ? $_POST['tags'] : null,
            'specs' => $_POST['specs'] ? $_POST['specs'] : null,
            'image' => $_POST['image'] ?? null,
        ];

        if ($action === 'store') {
            $data['uuid'] = uuid();
            $response = storePondHelper($filepond, $reference_model, $data, $attachments);
            if ($response['success']) {
                $response = $products->store($data);
            }
        } else if ($action === 'update') {
            $data['uuid'] = $_POST['uuid'] ?? null; // add product uuid to data, required for update
            $response = storePondHelper($filepond, $reference_model, $data, $attachments);
            if ($response['success']) {
                $response = $products->update($data);
            }
        } else {
            $response['message'] = 'Invalid POST action';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $action = $_GET['action'] ?? null;

        if ($action === 'all') {

            $result = $products->all();
            $result['data'] = array_map(function ($item) use ($categories, $reference_model) {
                // Format correct data
                $formattedData = [
                    'category_name' => Helpers::categoryName($categories->single($item['category_id'], $reference_model)['data']),
                    'status' => Helpers::productStatus($item['status']),
                    'tags' => $item['tags'] ? count(explode(',', $item['tags'])) : 0,
                    'specs' => $item['specs'] ? count(explode(',', $item['specs'])) : 0,
                    'created_at' => Formatters::dateToMDY($item['created_at']),
                    'updated_at' => Formatters::dateToMDY($item['updated_at']),
                ];

                // Remove unnecessary data
                unset($item['category_id'], $item['dc_price'], $item['features']);

                // Merge formatted data with the original item
                return array_merge($item, $formattedData);
            }, $result['data'] ?? []);
            $response = $result;

        } else if ($action === 'single') {
            $product_uuid = $_GET['uuid'] ?? null;
            $response = $products->single($product_uuid);
        } else {
            $response['message'] = 'Invalid GET action';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $product_uuid = $_GET['uuid'] ?? null;
        $response = $products->delete($product_uuid);
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;