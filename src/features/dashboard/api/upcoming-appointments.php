<?php

include '../../../core/app.php';
apiHeaders();

$response = [];

// Helper function to format time
function formatTime($time)
{
    if (!$time)
        return 'No time set';

    $timeParts = explode(':', $time);
    if (count($timeParts) >= 2) {
        $hour = intval($timeParts[0]);
        $minute = intval($timeParts[1]);
        return sprintf(
            '%d:%02d %s',
            $hour > 12 ? $hour - 12 : ($hour == 0 ? 12 : $hour),
            $minute,
            $hour >= 12 ? 'PM' : 'AM'
        );
    }
    return $time;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ensure user is logged in
    if (!$session->has()) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
        exit;
    }

    $userData = $session->get();
    $userUuid = $userData['uuid'];

    try {
        // Get ACCEPTED appointments - ONLY FUTURE DATES (today and beyond)
        $stmt = $conn->prepare("
            SELECT a.uuid,
                   a.service_uuid,
                   a.user_uuid,
                   a.pet_uuid,
                   a.date,
                   a.time,
                   a.status,
                   a.note,
                   'appointment' as type
            FROM appointments a
            WHERE a.user_uuid = ? 
            AND a.status = 'accepted'
            AND a.date >= CURDATE()
            ORDER BY a.date ASC, a.time ASC
            LIMIT 10
        ");

        $stmt->execute([$userUuid]);
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get READY_FOR_PICKUP product reservations - ONLY FUTURE DATES
        $reservationStmt = $conn->prepare("
            SELECT r.id as uuid,
                   r.user_uuid,
                   r.preferred_date as date,
                   r.preferred_time as time,
                   r.status,
                   r.products,
                   r.notes as note,
                   'reservation' as type
            FROM reservations r
            WHERE r.user_uuid = ? 
            AND r.status = 'ready_for_pickup'
            AND (r.preferred_date >= CURDATE() OR r.preferred_date IS NULL)
            ORDER BY r.preferred_date ASC, r.preferred_time ASC
            LIMIT 10
        ");

        $reservationStmt->execute([$userUuid]);
        $reservations = $reservationStmt->fetchAll(PDO::FETCH_ASSOC);

        // Combine appointments and reservations
        $allEvents = array_merge($appointments, $reservations);

        // Get the next 7 days starting from today
        $currentWeek = [];
        for ($i = 0; $i < 7; $i++) {
            $date = new DateTime("+$i day");
            $dateKey = $date->format('Y-m-d');
            $currentWeek[$dateKey] = [
                'date' => $dateKey,
                'day_name' => $date->format('D'),
                'day_number' => $date->format('j'),
                'is_today' => $i === 0,
                'appointments' => []
            ];
        }

        $formattedEvents = [];

        // Process each event
        foreach ($allEvents as $event) {
            if ($event['type'] === 'appointment') {
                // Process appointment
                $petName = 'Pet';
                $serviceName = 'Custom Service';

                // Get pet name
                if ($event['pet_uuid']) {
                    $petStmt = $conn->prepare("SELECT name FROM pets WHERE uuid = ?");
                    $petStmt->execute([$event['pet_uuid']]);
                    $pet = $petStmt->fetch(PDO::FETCH_ASSOC);
                    if ($pet) {
                        $petName = $pet['name'];
                    }
                }

                // Get service name
                if ($event['service_uuid']) {
                    $serviceStmt = $conn->prepare("SELECT name FROM services WHERE uuid = ?");
                    $serviceStmt->execute([$event['service_uuid']]);
                    $service = $serviceStmt->fetch(PDO::FETCH_ASSOC);
                    if ($service) {
                        $serviceName = $service['name'];
                    }
                } elseif ($event['note'] && strpos($event['note'], 'CUSTOM SERVICE REQUEST:') !== false) {
                    $serviceName = trim(str_replace('CUSTOM SERVICE REQUEST:', '', $event['note']));
                }

                $formattedEvent = [
                    'uuid' => $event['uuid'],
                    'type' => 'appointment',
                    'pet_name' => $petName,
                    'service_name' => $serviceName,
                    'date' => $event['date'],
                    'time' => $event['time'],
                    'status' => 'confirmed',
                    'formatted_time' => formatTime($event['time']),
                    'formatted_date' => date('M j', strtotime($event['date'])),
                    'day_name' => date('D', strtotime($event['date'])),
                    'is_today' => $event['date'] === date('Y-m-d'),
                    'pet_image' => $event['pet_uuid'] ? media($event['pet_uuid']) : asset('img/placeholders/image.png'),
                ];

            } else {
                // Process reservation (ready for pickup)
                $products = json_decode($event['products'], true) ?: [];
                $productCount = count($products);

                $formattedEvent = [
                    'uuid' => $event['uuid'],
                    'type' => 'pickup',
                    'pet_name' => null,
                    'service_name' => $productCount > 1 ? "$productCount Products Ready" : "Product Ready",
                    'date' => $event['date'] ?: date('Y-m-d'), // Use today if no date set
                    'time' => $event['time'],
                    'status' => 'ready',
                    'formatted_time' => formatTime($event['time']),
                    'formatted_date' => date('M j', strtotime($event['date'] ?: date('Y-m-d'))),
                    'day_name' => date('D', strtotime($event['date'] ?: date('Y-m-d'))),
                    'is_today' => ($event['date'] ?: date('Y-m-d')) === date('Y-m-d'),
                    'pet_image' => asset('img/placeholders/image.png'),
                ];
            }

            $formattedEvents[] = $formattedEvent;

            // Add to current week if within range
            $dateKey = $formattedEvent['date'];
            if (isset($currentWeek[$dateKey])) {
                $currentWeek[$dateKey]['appointments'][] = $formattedEvent;
            }
        }

        // Sort events by date and time
        usort($formattedEvents, function ($a, $b) {
            $dateCompare = strcmp($a['date'], $b['date']);
            if ($dateCompare !== 0)
                return $dateCompare;
            return strcmp($a['time'] ?: '00:00:00', $b['time'] ?: '00:00:00');
        });

        $response = [
            'success' => true,
            'data' => [
                'appointments' => $formattedEvents,
                'current_week' => array_values($currentWeek),
                'total_upcoming' => count($formattedEvents)
            ]
        ];

    } catch (PDOException $e) {
        error_log("Database error in upcoming appointments: " . $e->getMessage());
        $response = [
            'success' => false,
            'message' => 'Database error occurred'
        ];
    } catch (Exception $e) {
        error_log("General error in upcoming appointments: " . $e->getMessage());
        $response = [
            'success' => false,
            'message' => 'Error loading appointments'
        ];
    }

    echo json_encode($response);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request method']);
?>