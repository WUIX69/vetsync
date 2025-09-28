<?php

include '../../../core/app.php';
apiHeaders();

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ensure user is logged in
    if (!$session->has()) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
        exit;
    }

    $userData = $session->get();
    $userUuid = $userData['uuid'];

    try {
        // Get user's pets
        $petStmt = $conn->prepare("SELECT uuid, name, species, dob FROM pets WHERE user_uuid = ? AND archive_status = 'active'");
        $petStmt->execute([$userUuid]);
        $pets = $petStmt->fetchAll(PDO::FETCH_ASSOC);

        // Get completed appointments with proper category join
        $appointmentStmt = $conn->prepare("
            SELECT a.*, 
                   s.name as service_name, 
                   c.name as category_name,
                   p.name as pet_name
            FROM appointments a
            LEFT JOIN services s ON a.service_uuid = s.uuid
            LEFT JOIN categories c ON s.category_id = c.id
            LEFT JOIN pets p ON a.pet_uuid = p.uuid
            WHERE a.user_uuid = ? AND a.status = 'completed'
            ORDER BY a.date DESC
        ");
        $appointmentStmt->execute([$userUuid]);
        $completedAppointments = $appointmentStmt->fetchAll(PDO::FETCH_ASSOC);

        // Get next upcoming appointment (ACCEPTED appointments)
        $upcomingStmt = $conn->prepare("
            SELECT a.date, a.time, s.name as service_name, p.name as pet_name
            FROM appointments a
            LEFT JOIN services s ON a.service_uuid = s.uuid
            LEFT JOIN pets p ON a.pet_uuid = p.uuid
            WHERE a.user_uuid = ? AND a.status = 'accepted' AND a.date >= CURDATE()
            ORDER BY a.date ASC, a.time ASC
            LIMIT 1
        ");
        $upcomingStmt->execute([$userUuid]);
        $nextAppointment = $upcomingStmt->fetch(PDO::FETCH_ASSOC);

        // Get ready for pickup reservations count
        $pickupStmt = $conn->prepare("
            SELECT COUNT(*) as pickup_count
            FROM reservations 
            WHERE user_uuid = ? AND status = 'ready_for_pickup'
        ");
        $pickupStmt->execute([$userUuid]);
        $pickupData = $pickupStmt->fetch(PDO::FETCH_ASSOC);

        // Analyze each pet's health status
        $petHealthData = [];
        $totalVaccinations = 0;

        foreach ($pets as $pet) {
            $petAppointments = array_filter($completedAppointments, function ($appointment) use ($pet) {
                return $appointment['pet_uuid'] === $pet['uuid'];
            });

            $vaccinationAppointments = array_filter($petAppointments, function ($appointment) {
                // Check category name
                if (stripos($appointment['category_name'], 'vaccination') !== false) {
                    return true;
                }

                // Check service name for vaccination-related keywords
                $serviceName = strtolower($appointment['service_name']);
                $vaccinationKeywords = [
                    'vaccine',
                    'vaccination',
                    'rabies',
                    'anti-rabies',
                    'distemper',
                    'parvovirus',
                    'hepatitis',
                    'parainfluenza',
                    'bordetella',
                    'leptospirosis',
                    'lyme',
                    'feline',
                    'canine',
                    'immunization',
                    'shot',
                    'booster'
                ];

                foreach ($vaccinationKeywords as $keyword) {
                    if (stripos($serviceName, $keyword) !== false) {
                        return true;
                    }
                }

                return false;
            });

            $petHealthData[$pet['uuid']] = [
                'name' => $pet['name'],
                'total_appointments' => count($petAppointments),
                'vaccinations' => count($vaccinationAppointments),
                'last_visit' => !empty($petAppointments) ? $petAppointments[0]['date'] : null
            ];

            $totalVaccinations += count($vaccinationAppointments);
        }

        // Calculate average health score with better multi-pet logic
        $averageHealthScore = 0;
        $petsWithAppointments = 0;

        if (count($pets) > 0) {
            $totalScore = 0;

            foreach ($pets as $pet) {
                $petAppointments = array_filter($completedAppointments, function ($appointment) use ($pet) {
                    return $appointment['pet_uuid'] === $pet['uuid'];
                });

                $appointmentCount = count($petAppointments);

                // Only count pets that have had appointments (prevents new pets from dragging down score)
                if ($appointmentCount > 0) {
                    $petsWithAppointments++;

                    // More realistic scoring: 
                    // 1-2 appointments = 40%, 3-4 = 70%, 5+ = 100%
                    if ($appointmentCount <= 2) {
                        $petScore = 40;
                    } elseif ($appointmentCount <= 4) {
                        $petScore = 70;
                    } else {
                        $petScore = 100;
                    }

                    $totalScore += $petScore;
                }
            }

            // Calculate average only from pets that have received care
            if ($petsWithAppointments == 0) {
                $averageHealthScore = 50; // Neutral starting point for all new pets
            } else {
                $averageHealthScore = round($totalScore / $petsWithAppointments);
            }
        }

        // Better vaccination calculation - only count pets that have had appointments
        $petsWithCare = max(1, $petsWithAppointments); // Prevent division by zero
        $totalVaccinationsNeeded = $petsWithCare * 5; // Only count pets receiving care
        $vaccinationPercentage = $totalVaccinationsNeeded > 0 ?
            min(100, round(($totalVaccinations / $totalVaccinationsNeeded) * 100)) : 0;

        // Format next appointment
        $nextAppointmentFormatted = null;
        if ($nextAppointment) {
            $appointmentDate = new DateTime($nextAppointment['date']);
            $today = new DateTime();
            $daysUntil = $today->diff($appointmentDate)->days;

            $nextAppointmentFormatted = [
                'service' => $nextAppointment['service_name'] ?: 'Custom Service',
                'pet_name' => $nextAppointment['pet_name'],
                'date' => $nextAppointment['date'],
                'time' => $nextAppointment['time'],
                'formatted_date' => $appointmentDate->format('M j'),
                'days_until' => $daysUntil
            ];
        }

        // Determine health status
        $healthStatus = 'excellent';
        $healthMessage = 'All pets are doing well';
        $petInfo = '';

        if (count($pets) === 0) {
            $healthStatus = 'neutral';
            $healthMessage = 'No pets registered';
            $petInfo = 'Add your first pet to get started';
        } elseif (count($pets) === 1) {
            $pet = $pets[0];
            $petInfo = $pet['name'] . ' • ' . ($pet['species'] ?: 'Pet');
            if ($averageHealthScore < 40) {
                $healthStatus = 'poor';
                $healthMessage = 'Needs more care';
            } elseif ($averageHealthScore < 70) {
                $healthStatus = 'fair';
                $healthMessage = 'Regular checkups needed';
            }
        } else {
            // Multi-pet messaging
            if ($petsWithAppointments === 0) {
                $petInfo = count($pets) . ' pets registered - schedule first appointments';
                $healthMessage = 'New pets need initial checkups';
            } elseif ($petsWithAppointments < count($pets)) {
                $petInfo = $petsWithAppointments . ' of ' . count($pets) . ' pets receiving active care';
                $healthMessage = 'Some pets need attention';
            } else {
                $petInfo = 'All ' . count($pets) . ' pets receiving active care';
                $healthMessage = 'All pets are doing well';
            }
        }

        // Get the most recent visit date
        $lastVisitDate = null;
        if (!empty($completedAppointments)) {
            $lastVisitDate = $completedAppointments[0]['date'];
        }

        $stats = [
            'health_status' => $healthStatus,
            'health_message' => $healthMessage,
            'pet_info' => $petInfo,
            'total_pets' => count($pets),
            'pets_with_care' => $petsWithAppointments, // Add this new field
            'vaccination_count' => $totalVaccinations,
            'total_visits' => count($completedAppointments),
            'last_visit_date' => $lastVisitDate,
            'next_appointment' => $nextAppointmentFormatted,
            'pickup_count' => $pickupData['pickup_count'] ?? 0
        ];

        $progress = [
            'care_score' => min(100, $averageHealthScore),
            'vaccination' => $vaccinationPercentage
        ];

        $response = [
            'success' => true,
            'data' => [
                'stats' => $stats,
                'progress' => $progress,
                'pets' => $petHealthData
            ]
        ];

    } catch (PDOException $e) {
        error_log("Database error in pet health stats: " . $e->getMessage());
        $response = [
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ];
    } catch (Exception $e) {
        error_log("General error in pet health stats: " . $e->getMessage());
        $response = [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }

    echo json_encode($response);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request method']);
?>