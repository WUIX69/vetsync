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
                ORDER BY a.created_at DESC
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
                    uuid, booking_group_id, service_uuid, user_uuid, pet_uuid, date, note, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");

            $stmt->execute([
                $data['uuid'],
                $data['booking_group_id'] ?? null,
                $data['service_uuid'], // This can be null for custom services
                $data['user_uuid'],
                $data['pet_uuid'],
                $data['date'],
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
                    uuid, service_uuid, user_uuid, pet_uuid, date, note, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");

            $stmt->execute([
                $data['uuid'],
                $data['service_uuid'], // This can be null for custom services
                $data['user_uuid'],
                $data['pet_uuid'],
                $data['date'],
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

    public static function updateStatusWithReason($uuid, $status, $cancellationReason = null)
    {
        try {
            // Get appointment details for email notification
            $appointmentStmt = self::conn()->prepare('
                SELECT 
                    a.*, 
                    s.name AS service_name,
                    CONCAT(u.firstname, " ", u.lastname) AS user_name,
                    u.email AS user_email,
                    p.name AS pet_name
                FROM appointments a
                LEFT JOIN services s ON a.service_uuid = s.uuid
                LEFT JOIN users u ON a.user_uuid = u.uuid
                LEFT JOIN pets p ON a.pet_uuid = p.uuid
                WHERE a.uuid = ?
            ');
            $appointmentStmt->execute([$uuid]);
            $appointmentData = $appointmentStmt->fetch(PDO::FETCH_ASSOC);

            if ($status === 'cancelled' && $cancellationReason) {
                // Store cancellation reason in the note field
                $stmt = self::conn()->prepare('
                    UPDATE appointments 
                    SET status = ?, 
                        note = CONCAT(COALESCE(note, ""), 
                            CASE 
                                WHEN note IS NULL OR note = "" THEN ""
                                ELSE "\n\n"
                            END,
                            "[CANCELLED BY ADMIN] ", ?)
                    WHERE uuid = ?
                ');
                $stmt->execute([$status, $cancellationReason, $uuid]);
            } else {
                $stmt = self::conn()->prepare('
                    UPDATE appointments 
                    SET status = ?
                    WHERE uuid = ?
                ');
                $stmt->execute([$status, $uuid]);
            }

            if ($stmt->rowCount() > 0) {
                // Send email notification when appointment is accepted (confirmed)
                if ($status === 'accepted' && $appointmentData && $appointmentData['user_email']) {
                    try {
                        $emailService = new \VetSync\Services\Email();

                        // Get service name or custom service description
                        $serviceName = $appointmentData['service_name'] ?? 'Custom Service';
                        if (!$appointmentData['service_name'] && $appointmentData['note']) {
                            // Extract custom service from note if it starts with "CUSTOM SERVICE REQUEST:"
                            if (strpos($appointmentData['note'], 'CUSTOM SERVICE REQUEST:') === 0) {
                                $lines = explode("\n", $appointmentData['note']);
                                $serviceName = trim(str_replace('CUSTOM SERVICE REQUEST:', '', $lines[0]));
                            }
                        }

                        $emailResult = $emailService->sendAppointmentConfirmation(
                            $appointmentData['user_email'],
                            $appointmentData['user_name'],
                            $appointmentData['pet_name'],
                            $serviceName,
                            $appointmentData['date'],
                            $appointmentData['date'] // Using date field for time as well
                        );

                        if (!$emailResult['success']) {
                            error_log("Failed to send appointment confirmation email: " . $emailResult['message']);
                        }
                    } catch (Exception $e) {
                        error_log("Appointment confirmation email error: " . $e->getMessage());
                        // Don't fail the status update if email fails
                    }
                }

                return [
                    'success' => true,
                    'message' => 'Appointment status updated successfully.',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update appointment status.',
                ];
            }
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to update appointment status: ' . $e->getMessage(),
            ];
        }
    }

    public static function getByPetUuid($pet_uuid)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT COUNT(*) as appointment_count 
                FROM appointments 
                WHERE pet_uuid = ? AND status IN ("pending", "accepted")
            ');
            $stmt->execute([$pet_uuid]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return [
                'success' => true,
                'data' => $result,
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to check appointments: ' . $e->getMessage(),
            ];
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

    public static function reschedule($uuid, $new_date, $reason = '')
    {
        try {
            $stmt = self::conn()->prepare("
                UPDATE appointments 
                SET date = ?, 
                    note = CONCAT(COALESCE(note, ''), 
                        CASE 
                            WHEN note IS NULL OR note = '' THEN ''
                            ELSE '\n\n'
                        END,
                        '[RESCHEDULED BY ADMIN] ', ?, ' - ', NOW()),
                    updated_at = NOW()
                WHERE uuid = ?
            ");
            $stmt->execute([$new_date, $reason, $uuid]);
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