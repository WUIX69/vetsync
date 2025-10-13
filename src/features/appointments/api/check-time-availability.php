<?php

include '../../../core/app.php';
apiHeaders();

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        global $conn;

        $date = $_GET['date'] ?? '';

        if (empty($date)) {
            throw new Exception('Date is required');
        }

        // At the top, get selected service UUIDs and calculate total duration
        $selectedServiceUuids = $_GET['service_uuids'] ?? [];
        $requestedDuration = 0;

        if (!empty($selectedServiceUuids) && is_array($selectedServiceUuids)) {
            foreach ($selectedServiceUuids as $uuid) {
                $stmt = $conn->prepare("SELECT duration FROM services WHERE uuid = ? LIMIT 1");
                $stmt->execute([$uuid]);
                $service = $stmt->fetch(PDO::FETCH_ASSOC);
                $requestedDuration += $service ? (int) $service['duration'] : 60;
            }
        } else {
            $requestedDuration = 60; // Default
        }

        // Get all appointments for this date with their service durations
        $stmt = $conn->prepare("
            SELECT 
                a.time,
                a.booking_group_id,
                a.uuid,
                COALESCE(s.duration, 60) as service_duration
            FROM appointments a
            LEFT JOIN services s ON a.service_uuid = s.uuid
            WHERE DATE(a.date) = ?
            AND a.status != 'cancelled'
            AND a.time IS NOT NULL
            ORDER BY a.time ASC
        ");

        $stmt->execute([$date]);
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group appointments by booking_group_id or individual uuid
        $bookings = [];
        foreach ($appointments as $apt) {
            $key = $apt['booking_group_id'] ?: $apt['uuid'];

            if (!isset($bookings[$key])) {
                $bookings[$key] = [
                    'start_time' => $apt['time'],
                    'total_duration' => 0
                ];
            }

            $bookings[$key]['total_duration'] += (int) $apt['service_duration'];
        }

        // Calculate blocked time ranges
        $blockedRanges = [];
        foreach ($bookings as $booking) {
            $startTime = strtotime($booking['start_time']);
            $endTime = $startTime + ($booking['total_duration'] * 60); // Convert minutes to seconds

            $blockedRanges[] = [
                'start' => $startTime,
                'end' => $endTime
            ];
        }

        // Generate all possible time slots (30-minute intervals from 9 AM to 8 PM)
        $availableSlots = [];
        $currentTime = strtotime('09:00:00');
        $endOfDay = strtotime('20:00:00'); // Last slot at 8 PM
        $lunchStart = strtotime('12:00:00');
        $lunchEnd = strtotime('13:00:00');

        while ($currentTime <= $endOfDay) {
            // Skip lunch hour (12 PM - 1 PM)
            if ($currentTime >= $lunchStart && $currentTime < $lunchEnd) {
                $currentTime += 3600; // Skip to 1 PM
                continue;
            }

            // **NEW: Skip slots where appointment would extend past closing (8 PM)**
            $slotEndTime = $currentTime + ($requestedDuration * 60);
            $closingTime = strtotime('20:00:00');

            if ($slotEndTime > $closingTime) {
                // Don't show this slot - it would go past closing
                $currentTime += 3600;
                continue;
            }

            // Check if this time slot is available
            $isAvailable = true;
            $slotStart = $currentTime;

            foreach ($blockedRanges as $blocked) {
                // Check if the slot start time falls within a blocked range
                if ($slotStart >= $blocked['start'] && $slotStart < $blocked['end']) {
                    $isAvailable = false;
                    break;
                }
            }

            $availableSlots[] = [
                'value' => date('H:i:s', $currentTime),
                'label' => date('g:i A', $currentTime),
                'available' => $isAvailable
            ];

            $currentTime += 3600; // 1-hour intervals
        }

        $response = [
            'success' => true,
            'time_slots' => $availableSlots
        ];

    } catch (Exception $e) {
        error_log("Check time availability error: " . $e->getMessage());
        $response = [
            'success' => false,
            'message' => 'Error checking time availability: ' . $e->getMessage()
        ];
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid request method'];
}

echo json_encode($response);
exit;