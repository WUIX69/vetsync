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
                    c.name AS category_name,
                    CONCAT(u.firstname, " ", u.lastname) AS user_name,
                    u.email AS user_email,
                    u.telephone AS user_phone,
                    p.name AS pet_name,
                    p.breed AS pet_breed
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

    public static function store($data = [])
    {
        try {
            // Validate required fields
            if (
                empty($data['uuid']) || empty($data['service_uuid']) || empty($data['user_uuid']) ||
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
                $data['service_uuid'],
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
                        '[RESCHEDULED BY ADMIN] ', ?)
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
                    s.name AS service_name, 
                    c.name AS category_name,
                    CONCAT(u.firstname, " ", u.lastname) AS user_name,
                    u.email AS user_email,
                    u.telephone AS user_phone,
                    p.name AS pet_name,
                    p.breed AS pet_breed
                FROM appointments a
                LEFT JOIN services s ON a.service_uuid = s.uuid
                LEFT JOIN categories c ON s.category_id = c.id
                LEFT JOIN users u ON a.user_uuid = u.uuid
                LEFT JOIN pets p ON a.pet_uuid = p.uuid
                WHERE a.user_uuid = ?
                ORDER BY a.date DESC
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
                'message' => 'Failed to fetch user appointments: ' . $e->getMessage(),
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
}