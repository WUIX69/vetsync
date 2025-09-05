<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Cart;
use VetSync\Models\Products;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

try {
    // Use proper authentication like other files
    global $session;
    $user_uuid = $session->get()['uuid'] ?? null;

    // If no session, try userData() function
    if (!$user_uuid) {
        $userData = userData();
        $user_uuid = $userData['uuid'] ?? null;
    }

    // Final fallback for testing
    if (!$user_uuid) {
        $user_uuid = 'demo-user-' . session_id();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? null;

        if ($action === 'add') {
            $product_uuid = $_POST['product_uuid'] ?? '';
            $qty = intval($_POST['qty'] ?? 1);
            $size = $_POST['size'] ?? 'm';

            // Get product details to calculate total price
            $product = Products::single($product_uuid);
            if (!$product['success']) {
                $response = ['success' => false, 'message' => 'Product not found'];
            } else {
                $productData = $product['data'];
                $price = $productData['dc_price'] ?: $productData['og_price'];
                $total_price = floatval($price) * $qty;

                $response = Cart::add($user_uuid, $product_uuid, $qty, $size, $total_price);
            }
        } elseif ($action === 'update') {
            $product_uuid = $_POST['product_uuid'] ?? '';
            $qty = intval($_POST['qty'] ?? 1);
            $size = $_POST['size'] ?? 'm';

            // Get product details to calculate total price
            $product = Products::single($product_uuid);
            if (!$product['success']) {
                $response = ['success' => false, 'message' => 'Product not found'];
            } else {
                $productData = $product['data'];
                $price = $productData['dc_price'] ?: $productData['og_price'];
                $total_price = floatval($price) * $qty;

                $response = Cart::updateQuantity($user_uuid, $product_uuid, $size, $qty, $total_price);
            }
        } else {
            $response = ['success' => false, 'message' => 'Invalid action'];
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $action = $_GET['action'] ?? null;

        if ($action === 'items') {
            $response = Cart::getItems($user_uuid);
        } elseif ($action === 'count') {
            $countResponse = Cart::getCount($user_uuid);
            $itemsResponse = Cart::getItems($user_uuid);

            $response = [
                'success' => $countResponse['success'],
                'count' => $countResponse['count'],
                'items' => $itemsResponse['success'] ? $itemsResponse['data'] : []
            ];
        } else {
            $response = ['success' => false, 'message' => 'Invalid action'];
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $product_uuid = $_GET['product_uuid'] ?? '';
        $size = $_GET['size'] ?? null;

        if ($product_uuid === 'all') {
            $response = Cart::clear($user_uuid);
        } else {
            $response = Cart::remove($user_uuid, $product_uuid, $size);
        }
    }

} catch (Exception $e) {
    error_log("Cart API Error: " . $e->getMessage());
    $response = ['success' => false, 'message' => $e->getMessage()];
}

echo json_encode($response);
exit;
