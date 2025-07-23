<?php
namespace VetSync\Models;

use PDO;
use PDOException;

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
                    u.firstname AS user_name,
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
            $stmt = self::conn()->prepare("
                INSERT INTO appointments (
                    uuid, service_uuid, user_uuid, pet_uuid, date, note, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, NOW())
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
                'message' => 'Appointment booked successfully.',
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to book appointment: ' . $e->getMessage(),
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
}