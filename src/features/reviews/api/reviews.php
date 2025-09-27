<?php
include_once __DIR__ . '/../../../core/app.php';

use VetSync\Models\Reviews;
use VetSync\Models\Appointments;
use VetSync\Models\Reservations;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

switch ($action) {
    case 'submit_review':
        $user_uuid = $input['user_uuid'] ?? '';
        $reference_uuid = $input['reference_uuid'] ?? '';
        $reference_model = $input['reference_model'] ?? '';
        $rating = $input['rating'] ?? 0;
        $message = $input['message'] ?? '';

        // Validate inputs
        if (
            empty($user_uuid) || empty($reference_uuid) || empty($reference_model) ||
            empty($rating) || empty($message)
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'All fields are required'
            ]);
            exit;
        }

        // Check if user can review
        $canReview = Reviews::checkUserCanReview($user_uuid, $reference_uuid, $reference_model);

        if (!$canReview['can_review']) {
            $messages = [
                'already_reviewed' => 'You have already reviewed this item',
                'service_not_completed' => 'You can only review completed services',
                'product_not_picked_up' => 'You can only review products after pickup',
                'error' => 'Unable to verify eligibility'
            ];

            echo json_encode([
                'success' => false,
                'message' => $messages[$canReview['reason']] ?? 'Unable to submit review'
            ]);
            exit;
        }

        // Submit review
        $result = Reviews::store([
            'user_uuid' => $user_uuid,
            'reference_uuid' => $reference_uuid,
            'reference_model' => $reference_model,
            'rating' => intval($rating),
            'message' => trim($message)
        ]);

        echo json_encode($result);
        break;

    case 'get_reviews':
        $reference_uuid = $input['reference_uuid'] ?? '';
        $reference_model = $input['reference_model'] ?? '';

        if (empty($reference_uuid) || empty($reference_model)) {
            echo json_encode([
                'success' => false,
                'message' => 'Reference UUID and model are required'
            ]);
            exit;
        }

        $result = Reviews::getByReference($reference_uuid, $reference_model);
        echo json_encode($result);
        break;

    case 'check_can_review':
        $user_uuid = $input['user_uuid'] ?? '';
        $reference_uuid = $input['reference_uuid'] ?? '';
        $reference_model = $input['reference_model'] ?? '';

        if (empty($user_uuid) || empty($reference_uuid) || empty($reference_model)) {
            echo json_encode([
                'success' => false,
                'message' => 'All parameters are required'
            ]);
            exit;
        }

        $result = Reviews::checkUserCanReview($user_uuid, $reference_uuid, $reference_model);
        echo json_encode(['success' => true, 'data' => $result]);
        break;

    case 'update_review':
        $review_id = $input['review_id'] ?? '';
        $user_uuid = $input['user_uuid'] ?? '';
        $rating = $input['rating'] ?? '';
        $message = $input['message'] ?? '';

        if (empty($review_id) || empty($user_uuid) || empty($rating) || empty($message)) {
            echo json_encode([
                'success' => false,
                'message' => 'All fields are required'
            ]);
            exit;
        }

        $result = Reviews::updateReview($review_id, $user_uuid, [
            'rating' => $rating,
            'message' => $message
        ]);
        echo json_encode($result);
        break;

    case 'get_user_review':
        $user_uuid = $input['user_uuid'] ?? '';
        $reference_uuid = $input['reference_uuid'] ?? '';
        $reference_model = $input['reference_model'] ?? '';

        if (empty($user_uuid) || empty($reference_uuid) || empty($reference_model)) {
            echo json_encode([
                'success' => false,
                'message' => 'All parameters are required'
            ]);
            exit;
        }

        $result = Reviews::getUserReview($user_uuid, $reference_uuid, $reference_model);
        echo json_encode($result);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>