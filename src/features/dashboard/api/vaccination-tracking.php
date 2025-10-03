<?php

include '../../../core/app.php';
apiHeaders();

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!$session->has()) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
        exit;
    }

    $userData = $session->get();
    $userUuid = $userData['uuid'];

    try {
        // Get all vaccination appointments with service details
        $stmt = $conn->prepare("
            SELECT 
                a.uuid,
                a.pet_uuid,
                a.date,
                a.status,
                p.name as pet_name,
                s.name as service_name,
                s.uuid as service_uuid,
                c.name as category_name
            FROM appointments a
            LEFT JOIN pets p ON a.pet_uuid = p.uuid
            LEFT JOIN services s ON a.service_uuid = s.uuid
            LEFT JOIN categories c ON s.category_id = c.id
            WHERE a.user_uuid = ? 
            AND (c.name LIKE '%vaccination%' OR s.name LIKE '%vaccine%' OR s.name LIKE '%rabies%')
            ORDER BY p.name ASC, a.date DESC
        ");
        $stmt->execute([$userUuid]);
        $vaccinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group by pet and vaccine type
        $vaccinationTracking = [];

        foreach ($vaccinations as $vacc) {
            $petName = $vacc['pet_name'];
            $serviceName = $vacc['service_name'];
            $petUuid = $vacc['pet_uuid'];
            $serviceUuid = $vacc['service_uuid'];

            // Create unique key for pet + vaccine combination
            $key = $petUuid . '_' . $serviceUuid;

            if (!isset($vaccinationTracking[$key])) {
                $vaccinationTracking[$key] = [
                    'pet_name' => $petName,
                    'pet_uuid' => $petUuid,
                    'service_name' => $serviceName,
                    'service_uuid' => $serviceUuid,
                    'completed_sessions' => 0,
                    'total_sessions' => 3, // Default: most vaccines need 3 doses
                    'last_date' => null,
                    'next_recommended_date' => null,
                    'status' => 'not_started',
                    'sessions' => []
                ];
            }

            // Count completed sessions
            if ($vacc['status'] === 'completed') {
                $vaccinationTracking[$key]['completed_sessions']++;
                $vaccinationTracking[$key]['sessions'][] = [
                    'date' => $vacc['date'],
                    'status' => 'completed'
                ];

                // Update last date
                if (!$vaccinationTracking[$key]['last_date'] || $vacc['date'] > $vaccinationTracking[$key]['last_date']) {
                    $vaccinationTracking[$key]['last_date'] = $vacc['date'];
                }
            }
        }

        // Calculate next recommended dates and status
        foreach ($vaccinationTracking as &$tracking) {
            $completed = $tracking['completed_sessions'];
            $total = $tracking['total_sessions'];

            if ($completed === 0) {
                $tracking['status'] = 'not_started';
                $tracking['next_recommended_date'] = 'Book first session';
            } elseif ($completed < $total) {
                $tracking['status'] = 'ongoing';

                // Calculate next recommended date (2 weeks after last dose)
                if ($tracking['last_date']) {
                    $lastDate = new DateTime($tracking['last_date']);
                    $nextDate = clone $lastDate;
                    $nextDate->modify('+2 weeks'); // Recommend 2 weeks interval

                    $today = new DateTime();
                    $interval = $today->diff($nextDate);

                    if ($nextDate > $today) {
                        $tracking['next_recommended_date'] = $nextDate->format('M d, Y') . ' (in ' . $interval->days . ' days)';
                    } else {
                        $tracking['next_recommended_date'] = 'Overdue - Book now!';
                        $tracking['status'] = 'overdue';
                    }
                }
            } else {
                $tracking['status'] = 'completed';
                $tracking['next_recommended_date'] = 'Series completed!';
            }

            // Format last date
            if ($tracking['last_date']) {
                $tracking['last_date_formatted'] = date('M d, Y', strtotime($tracking['last_date']));
            }
        }

        $response = [
            'success' => true,
            'data' => array_values($vaccinationTracking)
        ];

    } catch (PDOException $e) {
        error_log("Vaccination tracking error: " . $e->getMessage());
        $response = [
            'success' => false,
            'message' => 'Error loading vaccination data: ' . $e->getMessage()
        ];
    }
}

echo json_encode($response);
exit;
