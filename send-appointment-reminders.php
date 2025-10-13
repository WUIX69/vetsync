<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/core/conn.php';
require_once __DIR__ . '/src/services/email.php';

use VetSync\Services\Email;

// Find appointments that are 2 days away and status is 'accepted'
$twoDaysFromNow = date('Y-m-d', strtotime('+2 days'));

$stmt = $conn->prepare("
    SELECT 
        a.*,
        CONCAT(u.firstname, ' ', u.lastname) AS user_name,
        u.email AS user_email,
        u.telephone AS user_phone,
        p.name AS pet_name,
        s.name AS service_name
    FROM appointments a
    LEFT JOIN users u ON a.user_uuid = u.uuid
    LEFT JOIN pets p ON a.pet_uuid = p.uuid
    LEFT JOIN services s ON a.service_uuid = s.uuid
    WHERE a.date = ?
    AND a.status = 'accepted'
    AND a.reminder_sent = 0
");

$stmt->execute([$twoDaysFromNow]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$emailService = new Email();
$sentCount = 0;

echo "Checking for appointments on {$twoDaysFromNow}...\n";
echo "Found " . count($appointments) . " appointment(s) to remind.\n\n";

foreach ($appointments as $appointment) {
    echo "Sending reminder to {$appointment['user_email']} for {$appointment['pet_name']}...\n";

    $result = $emailService->sendAppointmentReminder(
        $appointment['user_email'],
        $appointment['user_name'],
        $appointment['pet_name'],
        $appointment['service_name'] ?? 'Veterinary Service',
        $appointment['date'],
        $appointment['time'],
        $appointment['booking_group_id'] ?? '',
        $appointment['user_phone'] ?? ''
    );

    if ($result['success']) {
        // Mark as reminder sent
        $updateStmt = $conn->prepare("UPDATE appointments SET reminder_sent = 1 WHERE id = ?");
        $updateStmt->execute([$appointment['id']]);

        echo "✅ Reminder sent successfully!\n";
        $sentCount++;
    } else {
        echo "❌ Failed: {$result['message']}\n";
    }

    echo "\n";
}

echo "Completed! Sent {$sentCount} reminder(s).";