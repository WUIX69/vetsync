<?php
namespace VetSync\Models;

use PDO;
use PDOException;
use Exception;

class Appointments
{
    private static $conn;

    private static function conn()
    {
        if (!isset(self::$conn)) {
            global $conn;
            self::$conn = $conn;
        }
        return self::$conn;
    }

    public static function all()
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT 
                    a.*, 
                    s.name AS service_name,
                    s.description AS service_description,
                    c.name AS category_name,
                    CONCAT(u.firstname, " ", u.lastname) AS user_name,
                    u.email AS user_email,
                    u.telephone AS user_phone,
                    p.name AS pet_name,
                    p.breed AS pet_breed,
                    p.species AS pet_species,
                    p.dob AS pet_dob,
                    TIMESTAMPDIFF(YEAR, p.dob, CURDATE()) AS pet_age
                FROM appointments a
                LEFT JOIN services s ON a.service_uuid = s.uuid
                LEFT JOIN categories c ON s.category_id = c.id
                LEFT JOIN users u ON a.user_uuid = u.uuid
                LEFT JOIN pets p ON a.pet_uuid = p.uuid
                ORDER BY a.date ASC, a.created_at ASC
            ');
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
            return [
                'success' => true,
                'message' => 'Appointments fetched successfully.',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch appointments: ' . $e->getMessage(),
            ];
        }
    }

    public static function storeWithGroup($data = [])
    {
        try {
            // Validate required fields - allow service_uuid to be null for custom services
            if (
                empty($data['uuid']) || empty($data['user_uuid']) ||
                empty($data['pet_uuid']) || empty($data['date'])
            ) {
                throw new Exception('Missing required appointment data');
            }

            $stmt = self::conn()->prepare("
                INSERT INTO appointments (
                    uuid, booking_group_id, service_uuid, user_uuid, pet_uuid, date, time, note, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");

            $stmt->execute([
                $data['uuid'],
                $data['booking_group_id'] ?? null,
                $data['service_uuid'], // This can be null for custom services
                $data['user_uuid'],
                $data['pet_uuid'],
                $data['date'],
                $data['time'] ?? null,
                $data['note'] ?? ''
            ]);

            return [
                'success' => true,
                'message' => 'Appointment booked successfully! We will contact you to confirm.',
            ];
        } catch (PDOException $e) {
            error_log("Appointment booking error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to book appointment. Please try again.',
            ];
        } catch (Exception $e) {
            error_log("Appointment validation error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function storeMultiple($data = [])
    {
        try {
            // Validate required fields - allow service_uuid to be null for custom services
            if (
                empty($data['uuid']) || empty($data['user_uuid']) ||
                empty($data['pet_uuid']) || empty($data['date'])
            ) {
                throw new Exception('Missing required appointment data');
            }

            $stmt = self::conn()->prepare("
                INSERT INTO appointments (
                    uuid, booking_group_id, service_uuid, user_uuid, pet_uuid, date, time, note, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");

            $stmt->execute([
                $data['uuid'],
                $data['booking_group_id'] ?? null,
                $data['service_uuid'], // This can be null for custom services
                $data['user_uuid'],
                $data['pet_uuid'],
                $data['date'],
                $data['time'] ?? null, // Changed from time_slot to time
                $data['note']
            ]);

            return [
                'success' => true,
                'message' => 'Appointment booked successfully! We will contact you to confirm.',
            ];
        } catch (PDOException $e) {
            error_log("Appointment booking error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to book appointment. Please try again.',
            ];
        } catch (Exception $e) {
            error_log("Appointment validation error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function store($data = [])
    {
        try {
            // Validate required fields - allow service_uuid to be null for custom services
            if (
                empty($data['uuid']) || empty($data['user_uuid']) ||
                empty($data['pet_uuid']) || empty($data['date'])
            ) {
                throw new Exception('Missing required appointment data');
            }

            $stmt = self::conn()->prepare("
                INSERT INTO appointments (
                    uuid, service_uuid, user_uuid, pet_uuid, date, time, note, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");

            $stmt->execute([
                $data['uuid'],
                $data['service_uuid'], // This can be null for custom services
                $data['user_uuid'],
                $data['pet_uuid'],
                $data['date'],
                $data['time'] ?? null, // Changed from time_slot to time
                $data['note']
            ]);

            return [
                'success' => true,
                'message' => 'Appointment booked successfully! We will contact you to confirm.',
            ];
        } catch (PDOException $e) {
            error_log("Appointment booking error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to book appointment. Please try again.',
            ];
        } catch (Exception $e) {
            error_log("Appointment validation error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function updateStatus($uuid, $status)
    {
        try {
            $stmt = self::conn()->prepare("UPDATE appointments SET status = ? WHERE uuid = ?");
            $stmt->execute([$status, $uuid]);
            return [
                'success' => true,
                'message' => 'Status updated successfully.',
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ];
        }
    }

    // Update the updateStatusWithReason function to handle groups AND create notifications
    public static function updateStatusWithReason($uuid, $status, $reason = '')
    {
        try {
            // Check if this appointment is part of a group
            $checkStmt = self::conn()->prepare("
                SELECT 
                    a.booking_group_id, 
                    a.user_uuid, 
                    a.date,
                    a.time,
                    p.name as pet_name,
                    s.name as service_name
                FROM appointments a
                LEFT JOIN pets p ON a.pet_uuid = p.uuid
                LEFT JOIN services s ON a.service_uuid = s.uuid
                WHERE a.uuid = ?
            ");
            $checkStmt->execute([$uuid]);
            $appointment = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$appointment) {
                return ['success' => false, 'message' => 'Appointment not found'];
            }

            $userUuid = $appointment['user_uuid'];
            $petName = $appointment['pet_name'] ?? 'Your pet';
            $serviceName = $appointment['service_name'] ?? 'appointment';
            $appointmentDate = date('M d, Y', strtotime($appointment['date']));
            $appointmentTime = $appointment['time'] ?? 'Not set';

            if ($appointment && $appointment['booking_group_id']) {
                // Update entire group
                $groupId = $appointment['booking_group_id'];

                $stmt = self::conn()->prepare("
                    UPDATE appointments 
                    SET status = ?, 
                        note = CASE 
                            WHEN ? != '' THEN CONCAT(COALESCE(note, ''), 
                                CASE 
                                    WHEN note IS NULL OR note = '' THEN ''
                                    ELSE '\n\n'
                                END,
                                '[', UPPER(?), ' - GROUP] ', ?, ' - ', NOW())
                            ELSE note
                        END,
                        updated_at = NOW()
                    WHERE booking_group_id = ?
                ");
                $stmt->execute([$status, $reason, $status, $reason, $groupId]);

                // Health recovery logic for completed status
                if ($status === 'completed') {
                    $healthStmt = self::conn()->prepare("
                            UPDATE users 
                            SET user_health = LEAST(user_health + 5, 100) 
                            WHERE uuid = ?
                        ");
                    $healthStmt->execute([$userUuid]);
                }

                // CREATE NOTIFICATION for group appointment
                self::createNotification($userUuid, 'appointment', $uuid, $status, $petName, $serviceName, $appointmentDate, $appointmentTime, true);

                return [
                    'success' => true,
                    'message' => 'Group appointment updated successfully. All services in this booking have been updated.',
                ];
            } else {
                // Update single appointment (existing logic)
                $stmt = self::conn()->prepare("
                    UPDATE appointments 
                    SET status = ?, 
                        note = CASE 
                            WHEN ? != '' THEN CONCAT(COALESCE(note, ''), 
                                CASE 
                                    WHEN note IS NULL OR note = '' THEN ''
                                    ELSE '\n\n'
                                END,
                                '[', UPPER(?), '] ', ?, ' - ', NOW())
                            ELSE note
                        END,
                        updated_at = NOW()
                    WHERE uuid = ?
                ");
                $stmt->execute([$status, $reason, $status, $reason, $uuid]);

                // Health recovery for single appointment
                if ($status === 'completed') {
                    $healthStmt = self::conn()->prepare("
                            UPDATE users 
                            SET user_health = LEAST(user_health + 5, 100) 
                            WHERE uuid = ?
                        ");
                    $healthStmt->execute([$userUuid]);
                }

                // CREATE NOTIFICATION for single appointment
                self::createNotification($userUuid, 'appointment', $uuid, $status, $petName, $serviceName, $appointmentDate, $appointmentTime, false);

                return [
                    'success' => true,
                    'message' => 'Appointment updated successfully.',
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to update appointment status: ' . $e->getMessage(),
            ];
        }
    }

    // NEW FUNCTION: Create notification helper
    private static function createNotification($userUuid, $type, $referenceId, $status, $petName, $serviceName, $date, $time, $isGroup = false)
    {
        try {
            $conn = self::conn();

            // Determine notification details based on status
            $notifications = [
                'accepted' => [
                    'title' => 'Appointment Confirmed',
                    'message' => "Your appointment for {$petName}'s {$serviceName} on {$date} at {$time} has been confirmed." . ($isGroup ? " (Group booking)" : ""),
                    'icon' => 'check-circle',
                    'color' => 'blue',
                    'link' => '/src/app/user/appointments.php'
                ],
                'completed' => [
                    'title' => 'Appointment Completed',
                    'message' => "Your appointment for {$petName}'s {$serviceName} has been completed. Thank you for visiting!" . ($isGroup ? " (Group booking)" : ""),
                    'icon' => 'check',
                    'color' => 'green',
                    'link' => '/src/app/user/appointments.php'
                ],
                'cancelled' => [
                    'title' => 'Appointment Cancelled',
                    'message' => "Your appointment for {$petName}'s {$serviceName} on {$date} has been cancelled." . ($isGroup ? " (Group booking)" : ""),
                    'icon' => 'times-circle',
                    'color' => 'red',
                    'link' => '/src/app/user/appointments.php'
                ],
                'pending' => [
                    'title' => 'Appointment Pending',
                    'message' => "Your appointment for {$petName}'s {$serviceName} is pending confirmation." . ($isGroup ? " (Group booking)" : ""),
                    'icon' => 'clock',
                    'color' => 'orange',
                    'link' => '/src/app/user/appointments.php'
                ]
            ];

            $notif = $notifications[$status] ?? null;

            if (!$notif) {
                return; // Skip if status is unknown
            }

            // Insert notification
            $stmt = $conn->prepare("
                INSERT INTO notifications (user_uuid, type, reference_id, title, message, icon, color, link, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");

            $stmt->execute([
                $userUuid,
                $type,
                $referenceId,
                $notif['title'],
                $notif['message'],
                $notif['icon'],
                $notif['color'],
                $notif['link']
            ]);

        } catch (PDOException $e) {
            // Log error but don't fail the main operation
            error_log("Failed to create notification: " . $e->getMessage());
        }
    }

    public static function getCompletedByPetUuid($pet_uuid)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT 
                    a.*, 
                    s.name AS service_name, 
                    s.category_id,
                    c.name AS category_name,
                    c.icon AS category_icon,
                    a.date AS appointment_date,
                    a.created_at AS appointment_created
                FROM appointments a
                LEFT JOIN services s ON a.service_uuid = s.uuid
                LEFT JOIN categories c ON s.category_id = c.id
                WHERE a.pet_uuid = ? AND a.status = "completed"
                ORDER BY a.date DESC
            ');
            $stmt->execute([$pet_uuid]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

            return [
                'success' => true,
                'message' => 'Completed appointments fetched successfully.',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch completed appointments: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    public static function reschedule($uuid, $newDate, $reason = '')
    {
        try {
            // Get appointment details for notification
            $checkStmt = self::conn()->prepare("
                SELECT 
                    a.user_uuid, 
                    p.name as pet_name,
                    s.name as service_name,
                    a.time
                FROM appointments a
                LEFT JOIN pets p ON a.pet_uuid = p.uuid
                LEFT JOIN services s ON a.service_uuid = s.uuid
                WHERE a.uuid = ?
            ");
            $checkStmt->execute([$uuid]);
            $appointment = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$appointment) {
                return ['success' => false, 'message' => 'Appointment not found'];
            }

            // Update appointment
            $stmt = self::conn()->prepare("
                UPDATE appointments 
                SET date = ?,
                    note = CONCAT(COALESCE(note, ''), 
                        CASE 
                            WHEN note IS NULL OR note = '' THEN ''
                            ELSE '\n\n'
                        END,
                        '[RESCHEDULED] ', ?, ' - ', NOW()),
                    updated_at = NOW()
                WHERE uuid = ?
            ");

            $stmt->execute([$newDate, $reason, $uuid]);

            // CREATE RESCHEDULE NOTIFICATION
            $newDateFormatted = date('M d, Y', strtotime($newDate));
            $petName = $appointment['pet_name'] ?? 'Your pet';
            $serviceName = $appointment['service_name'] ?? 'appointment';
            $time = $appointment['time'] ?? 'Not set';

            $notifStmt = self::conn()->prepare("
                INSERT INTO notifications (user_uuid, type, reference_id, title, message, icon, color, link, created_at)
                VALUES (?, 'appointment', ?, 'Appointment Rescheduled', ?, 'calendar', 'orange', '/src/app/user/appointments.php', NOW())
            ");

            $notifStmt->execute([
                $appointment['user_uuid'],
                $uuid,
                "Your appointment for {$petName}'s {$serviceName} has been rescheduled to {$newDateFormatted} at {$time}."
            ]);

            return [
                'success' => true,
                'message' => 'Appointment rescheduled successfully.',
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to reschedule appointment: ' . $e->getMessage(),
            ];
        }
    }

    public static function getByUserUuid($userUuid)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT 
                    a.*, 
                    a.booking_group_id,
                    s.name AS service_name, 
                    s.uuid AS service_uuid,
                    c.name AS category_name,
                    CONCAT(u.firstname, " ", u.lastname) AS user_name,
                    u.email AS user_email,
                    u.telephone AS user_phone,
                    p.name AS pet_name,
                    p.breed AS pet_breed,
                    r.id AS review_id,
                    r.rating AS review_rating,
                    r.message AS review_message
                FROM appointments a
                LEFT JOIN services s ON a.service_uuid = s.uuid
                LEFT JOIN categories c ON s.category_id = c.id
                LEFT JOIN users u ON a.user_uuid = u.uuid
                LEFT JOIN pets p ON a.pet_uuid = p.uuid
                LEFT JOIN reviews r ON (r.reference_uuid = a.uuid AND r.reference_model = "appointment" AND r.user_uuid = a.user_uuid)
                WHERE a.user_uuid = ?
                ORDER BY a.booking_group_id DESC, a.date DESC
            ');
            $stmt->execute([$userUuid]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
            return [
                'success' => true,
                'message' => 'User appointments fetched successfully.',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    public static function delete($uuid)
    {
        try {
            $stmt = self::conn()->prepare('DELETE FROM appointments WHERE uuid = ?');
            $stmt->execute([$uuid]);

            if ($stmt->rowCount() > 0) {
                return [
                    'success' => true,
                    'message' => 'Appointment deleted successfully.',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Appointment not found or already deleted.',
                ];
            }
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to delete appointment: ' . $e->getMessage(),
            ];
        }
    }

    public static function single($uuid)
    {
        try {
            $stmt = self::conn()->prepare('SELECT * FROM appointments WHERE uuid = ? LIMIT 1');
            $stmt->execute([$uuid]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return [
                'success' => true,
                'data' => $data ?: []
            ];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function getByUuidWithDetails($uuid)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT 
                    a.*, 
                    s.name AS service_name,
                    s.description AS service_description,
                    c.name AS category_name,
                    CONCAT(u.firstname, " ", u.lastname) AS owner_name,
                    u.email AS owner_email,
                    u.telephone AS owner_phone,
                    p.name AS pet_name,
                    p.breed AS pet_breed,
                    p.species AS pet_species,
                    p.age AS pet_age
                FROM appointments a
                LEFT JOIN services s ON a.service_uuid = s.uuid
                LEFT JOIN categories c ON s.category_id = c.id
                LEFT JOIN users u ON a.user_uuid = u.uuid
                LEFT JOIN pets p ON a.pet_uuid = p.uuid
                WHERE a.uuid = :uuid
            ');
            $stmt->bindParam(':uuid', $uuid);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching appointment details: " . $e->getMessage());
            return false;
        }
    }

    public static function updateStatusToCompleted($uuid)
    {
        try {
            $stmt = self::conn()->prepare("
                UPDATE appointments 
                SET status = 'completed', updated_at = NOW() 
                WHERE uuid = ?
            ");
            $stmt->execute([$uuid]);

            if ($stmt->rowCount() > 0) {
                return [
                    'success' => true,
                    'message' => 'Service marked as completed successfully.',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Appointment not found.',
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ];
        }
    }

    public static function getCompletedByUser($user_uuid)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT 
                    a.*, 
                    s.name AS service_name,
                    s.uuid AS service_uuid,
                    r.id AS review_id
                FROM appointments a
                LEFT JOIN services s ON a.service_uuid = s.uuid
                LEFT JOIN reviews r ON (r.reference_uuid = a.uuid AND r.reference_model = "appointments" AND r.user_uuid = ?)
                WHERE a.user_uuid = ? AND a.status = "completed"
                ORDER BY a.date DESC
            ');
            $stmt->execute([$user_uuid, $user_uuid]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

            return [
                'success' => true,
                'data' => $data,
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch completed appointments: ' . $e->getMessage(),
            ];
        }
    }
}