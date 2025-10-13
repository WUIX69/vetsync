<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Models\Appointments;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response = [
        'success' => false,
        'message' => 'Invalid request method',
        'data' => []
    ];
    echo json_encode($response);
    exit;
}

$response = [
    'success' => false,
    'message' => 'Unknown error',
    'data' => []
];

try {
    $pet_uuid = $_GET['pet_uuid'] ?? null;

    if (empty($pet_uuid)) {
        $response = [
            'success' => false,
            'message' => 'Pet UUID is required',
            'data' => []
        ];
    } else {
        // Get completed appointments for the pet
        $appointmentsResult = Appointments::getCompletedByPetUuid($pet_uuid);

        error_log("Appointments result: " . json_encode($appointmentsResult));

        if (!$appointmentsResult['success']) {
            $response = [
                'success' => false,
                'message' => $appointmentsResult['message'] ?? 'Failed to fetch appointments',
                'data' => []
            ];
        } else {
            $appointments = $appointmentsResult['data'] ?? [];
            error_log("Found " . count($appointments) . " completed appointments");

            // Filter only important services
            $importantCategories = ['vaccination', 'surgery', 'checkup', 'treatment', 'emergency', 'injection'];
            $filteredAppointments = [];

            foreach ($appointments as $appointment) {
                $categoryName = strtolower($appointment['category_name'] ?? '');
                $serviceName = strtolower($appointment['service_name'] ?? '');

                error_log("Checking: " . $appointment['service_name'] . " | Category: " . $appointment['category_name']);

                $isImportant = false;
                foreach ($importantCategories as $important) {
                    if (strpos($categoryName, $important) !== false || strpos($serviceName, $important) !== false) {
                        $isImportant = true;
                        break;
                    }
                }

                if ($isImportant) {
                    $filteredAppointments[] = $appointment;
                    error_log("Added to results: " . $appointment['service_name']);
                } else {
                    error_log("Filtered out: " . $appointment['service_name'] . " (Category: " . $appointment['category_name'] . ")");
                }
            }

            $response = [
                'success' => true,
                'message' => 'Service history fetched successfully',
                'data' => $filteredAppointments
            ];

            error_log("Final result: " . count($filteredAppointments) . " services after filtering");
        }
    }

} catch (Exception $e) {
    error_log("Exception in pet-service-history.php: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'An error occurred while fetching service history: ' . $e->getMessage(),
        'data' => []
    ];
}

echo json_encode($response);
exit;
