<?php

namespace VetSync\Models;

use PDO;
use PDOException;

class Reservations
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

    public static function store($data)
    {
        try {
            $stmt = self::conn()->prepare('
                INSERT INTO reservations (id, user_uuid, products, preferred_date, preferred_time, delivery_method, notes, total_amount, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ');

            $stmt->execute([
                $data['id'],
                $data['user_uuid'],
                $data['products'], // JSON encoded products
                $data['preferred_date'],
                $data['preferred_time'],
                $data['delivery_method'],
                $data['notes'],
                $data['total_amount'],
                'pending'
            ]);

            return [
                'success' => true,
                'message' => 'Reservation created successfully.',
                'id' => $data['id']
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to create reservation: ' . $e->getMessage(),
            ];
        }
    }

    public static function all()
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT r.*, u.firstname, u.lastname, u.email 
                FROM reservations r
                JOIN users u ON r.user_uuid = u.uuid
                ORDER BY r.created_at DESC
            ');
            $stmt->execute();
            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Decode products JSON and add formatted data
            $formattedReservations = array_map(function ($reservation) {
                $products = json_decode($reservation['products'], true) ?? [];
                $reservation['products_array'] = $products;
                $reservation['products_count'] = count($products);
                $reservation['formatted_date'] = date('F j, Y', strtotime($reservation['preferred_date']));
                $reservation['formatted_time'] = date('g:i A', strtotime($reservation['preferred_time']));
                return $reservation;
            }, $reservations);

            return [
                'success' => true,
                'data' => $formattedReservations,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to fetch reservations: ' . $e->getMessage(),
            ];
        }
    }

    public static function updateStatus($id, $status, $reason = null)
    {
        try {
            $stmt = self::conn()->prepare('
                UPDATE reservations 
                SET status = ?, rejection_reason = ?, updated_at = NOW() 
                WHERE id = ?
            ');
            $stmt->execute([$status, $reason, $id]);

            return [
                'success' => true,
                'message' => "Reservation {$status} successfully.",
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to update reservation: ' . $e->getMessage(),
            ];
        }
    }

    public static function getByUser($user_uuid)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT * FROM reservations 
                WHERE user_uuid = ? 
                ORDER BY created_at DESC
            ');
            $stmt->execute([$user_uuid]);
            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format data
            $formattedReservations = array_map(function ($reservation) {
                $products = json_decode($reservation['products'], true) ?? [];
                $reservation['products_array'] = $products;
                $reservation['products_count'] = count($products);
                $reservation['formatted_date'] = date('F j, Y', strtotime($reservation['preferred_date']));
                $reservation['formatted_time'] = date('g:i A', strtotime($reservation['preferred_time']));
                return $reservation;
            }, $reservations);

            return [
                'success' => true,
                'data' => $formattedReservations,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to fetch user reservations: ' . $e->getMessage(),
            ];
        }
    }

    public static function getById($id)
    {
        try {
            $stmt = self::conn()->prepare('SELECT * FROM reservations WHERE id = ?');
            $stmt->execute([$id]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($reservation) {
                return [
                    'success' => true,
                    'data' => $reservation,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Reservation not found',
                ];
            }
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to fetch reservation: ' . $e->getMessage(),
            ];
        }
    }
}
