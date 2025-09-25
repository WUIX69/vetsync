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

        // Get next upcoming appointment
        $upcomingStmt = $conn->prepare("
            SELECT a.date, a.time, s.name as service_name, p.name as pet_name
            FROM appointments a
            LEFT JOIN services s ON a.service_uuid = s.uuid
            LEFT JOIN pets p ON a.pet_uuid = p.uuid
            WHERE a.user_uuid = ? AND a.status = 'accepted' AND a.date >= CURDATE()
            ORDER BY a.date ASC
            LIMIT 1
        ");
        $upcomingStmt->execute([$userUuid]);
        $nextAppointment = $upcomingStmt->fetch(PDO::FETCH_ASSOC);

        // Analyze each pet's health status
        $petHealthData = [];
        foreach ($pets as $pet) {
            $petDob = new DateTime($pet['dob']);
            $petAge = $petDob->diff(new DateTime())->y;

            // Get appointments for this specific pet
            $petAppointments = array_filter($completedAppointments, function ($apt) use ($pet) {
                return $apt['pet_uuid'] === $pet['uuid'];
            });

            $petVaccinations = 0;
            $petCheckups = 0;
            $lastVisit = null;

            foreach ($petAppointments as $appointment) {
                $categoryName = strtolower($appointment['category_name'] ?? '');
                $serviceName = strtolower($appointment['service_name'] ?? '');

                if (
                    strpos($categoryName, 'vaccination') !== false ||
                    strpos($serviceName, 'vaccin') !== false ||
                    strpos($serviceName, 'injection') !== false ||
                    strpos($serviceName, 'rabies') !== false
                ) {
                    $petVaccinations++;
                }

                if (
                    strpos($categoryName, 'checkup') !== false ||
                    strpos($serviceName, 'checkup') !== false ||
                    strpos($serviceName, 'exam') !== false
                ) {
                    $petCheckups++;
                }

                if (!$lastVisit || $appointment['date'] > $lastVisit) {
                    $lastVisit = $appointment['date'];
                }
            }

            // Calculate health priority (lower number = needs more attention)
            $healthPriority = 100;

            // Deduct points for good care
            $healthPriority -= ($petVaccinations * 15); // Each vaccination reduces priority
            $healthPriority -= ($petCheckups * 10); // Each checkup reduces priority
            $healthPriority -= (count($petAppointments) * 5); // Each visit reduces priority

            // Add points for age (older pets need more attention)
            if ($petAge > 7)
                $healthPriority += 20; // Senior pets
            elseif ($petAge > 3)
                $healthPriority += 10; // Adult pets

            // Add points if no recent visits
            if ($lastVisit) {
                $daysSinceLastVisit = (new DateTime())->diff(new DateTime($lastVisit))->days;
                if ($daysSinceLastVisit > 365)
                    $healthPriority += 25; // No visit in over a year
                elseif ($daysSinceLastVisit > 180)
                    $healthPriority += 15; // No visit in 6+ months
            } else {
                $healthPriority += 30; // Never visited
            }

            $petHealthData[] = [
                'pet' => $pet,
                'age' => $petAge,
                'vaccinations' => $petVaccinations,
                'checkups' => $petCheckups,
                'total_visits' => count($petAppointments),
                'last_visit' => $lastVisit,
                'health_priority' => max(0, $healthPriority), // Don't go below 0
                'needs_attention' => $healthPriority > 50
            ];
        }

        // Sort by health priority (highest priority first)
        usort($petHealthData, function ($a, $b) {
            return $b['health_priority'] - $a['health_priority'];
        });

        // Determine what to show in health card
        $totalPets = count($pets);

        if ($totalPets === 0) {
            $healthMessage = 'Add pets to track health';
            $healthStatus = 'unknown';
            $petInfo = null;
        } elseif ($totalPets === 1) {
            // Single pet - show that pet's info
            $pet = $petHealthData[0];
            if ($pet['health_priority'] > 70) {
                $healthMessage = $pet['pet']['name'] . ' needs immediate attention';
                $healthStatus = 'needs_checkup';
            } elseif ($pet['health_priority'] > 50) {
                $healthMessage = $pet['pet']['name'] . ' needs a checkup';
                $healthStatus = 'fair';
            } elseif ($pet['health_priority'] > 30) {
                $healthMessage = $pet['pet']['name'] . ' is doing okay';
                $healthStatus = 'good';
            } else {
                $healthMessage = $pet['pet']['name'] . ' is in excellent health!';
                $healthStatus = 'excellent';
            }
            $petInfo = $pet['pet']['name'] . ' • ' . $pet['age'] . ' years old';
        } else {
            // Multiple pets - show most critical info
            $petsNeedingAttention = array_filter($petHealthData, function ($p) {
                return $p['needs_attention']; });
            $totalVaccinations = array_sum(array_column($petHealthData, 'vaccinations'));
            $totalVisits = array_sum(array_column($petHealthData, 'total_visits'));

            if (count($petsNeedingAttention) > 0) {
                // Show the pet that needs most attention
                $criticalPet = $petsNeedingAttention[0];
                $healthMessage = $criticalPet['pet']['name'] . ' needs attention';
                $healthStatus = 'needs_checkup';
                $petInfo = count($petsNeedingAttention) . ' of ' . $totalPets . ' pets need care';
            } else {
                // All pets are doing well
                $healthMessage = 'All ' . $totalPets . ' pets are healthy!';
                $healthStatus = 'excellent';
                $petInfo = $totalVaccinations . ' total vaccinations • ' . $totalVisits . ' total visits';
            }
        }

        // Count total appointments by type
        $vaccinationCount = 0;
        $checkupCount = 0;
        $groomingCount = 0;
        $totalVisits = count($completedAppointments);

        foreach ($completedAppointments as $appointment) {
            $categoryName = strtolower($appointment['category_name'] ?? '');
            $serviceName = strtolower($appointment['service_name'] ?? '');

            if (
                strpos($categoryName, 'vaccination') !== false ||
                strpos($serviceName, 'vaccin') !== false ||
                strpos($serviceName, 'injection') !== false ||
                strpos($serviceName, 'rabies') !== false
            ) {
                $vaccinationCount++;
            } elseif (
                strpos($categoryName, 'checkup') !== false ||
                strpos($serviceName, 'checkup') !== false ||
                strpos($serviceName, 'exam') !== false
            ) {
                $checkupCount++;
            } elseif (
                strpos($categoryName, 'grooming') !== false ||
                strpos($serviceName, 'groom') !== false
            ) {
                $groomingCount++;
            }
        }

        // Format next appointment
        $nextAppointmentData = null;
        if ($nextAppointment) {
            $appointmentDate = new DateTime($nextAppointment['date']);
            $today = new DateTime();
            $daysUntil = $appointmentDate->diff($today)->days;

            $nextAppointmentData = [
                'service' => $nextAppointment['service_name'] ?? 'Custom Service',
                'pet_name' => $nextAppointment['pet_name'] ?? '',
                'formatted_date' => $appointmentDate->format('M j, Y'),
                'days_until' => $daysUntil
            ];
        }

        // Calculate progress percentages based on total pets
        $expectedVaccinations = $totalPets * 5; // 5 core vaccines per pet
        $vaccinationProgress = $expectedVaccinations > 0 ? min(100, ($vaccinationCount / $expectedVaccinations) * 100) : 0;
        $checkupProgress = $totalPets > 0 ? min(100, ($checkupCount / $totalPets) * 100) : 0;
        $careScore = $totalPets > 0 ? min(100, ($totalVisits / ($totalPets * 3)) * 100) : 0;

        $lastVisitDate = null;
        if (!empty($completedAppointments)) {
            $lastVisitDate = $completedAppointments[0]['date'];
        }

        $response = [
            'success' => true,
            'data' => [
                'stats' => [
                    'total_pets' => $totalPets,
                    'total_visits' => $totalVisits,
                    'vaccination_count' => $vaccinationCount,
                    'checkup_count' => $checkupCount,
                    'grooming_count' => $groomingCount,
                    'health_status' => $healthStatus,
                    'health_message' => $healthMessage,
                    'pet_info' => $petInfo,
                    'last_visit_date' => $lastVisitDate,
                    'next_appointment' => $nextAppointmentData
                ],
                'progress' => [
                    'vaccination' => round($vaccinationProgress),
                    'checkup' => round($checkupProgress),
                    'care_score' => round($careScore)
                ]
            ]
        ];

    } catch (PDOException $e) {
        error_log("Database error in pet health stats: " . $e->getMessage());
        $response = [
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ];
    }

    echo json_encode($response);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request method']);
?>