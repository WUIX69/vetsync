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
                $data['delivery_method'] ?? 'pickup',
                $data['notes'] ?? null,
                $data['total_amount'] ?? 0,
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
                LEFT JOIN users u ON r.user_uuid = u.uuid
                ORDER BY r.created_at DESC
            ');
            $stmt->execute();
            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Safely decode products JSON and add formatted data
            $formattedReservations = [];
            foreach ($reservations as $reservation) {
                // Safely decode JSON
                $products = [];
                if (!empty($reservation['products'])) {
                    $decoded = json_decode($reservation['products'], true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $products = $decoded;
                    }
                }

                $reservation['products_array'] = $products;
                $reservation['products_count'] = count($products);

                // Safe date formatting
                if (!empty($reservation['preferred_date'])) {
                    $reservation['formatted_date'] = date('F j, Y', strtotime($reservation['preferred_date']));
                } else {
                    $reservation['formatted_date'] = 'Date not set';
                }

                if (!empty($reservation['preferred_time'])) {
                    $reservation['formatted_time'] = date('g:i A', strtotime($reservation['preferred_time']));
                } else {
                    $reservation['formatted_time'] = 'Time not set';
                }

                $formattedReservations[] = $reservation;
            }

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

            if ($stmt->rowCount() > 0) {
                return [
                    'success' => true,
                    'message' => "Reservation {$status} successfully.",
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Reservation not found or already updated.',
                ];
            }
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

            // Format data safely
            $formattedReservations = [];
            foreach ($reservations as $reservation) {
                // Safely decode JSON
                $products = [];
                if (!empty($reservation['products'])) {
                    $decoded = json_decode($reservation['products'], true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $products = $decoded;
                    }
                }

                $reservation['products_array'] = $products;
                $reservation['products_count'] = count($products);

                // Safe date formatting
                if (!empty($reservation['preferred_date'])) {
                    $reservation['formatted_date'] = date('F j, Y', strtotime($reservation['preferred_date']));
                } else {
                    $reservation['formatted_date'] = 'Date not set';
                }

                if (!empty($reservation['preferred_time'])) {
                    $reservation['formatted_time'] = date('g:i A', strtotime($reservation['preferred_time']));
                } else {
                    $reservation['formatted_time'] = 'Time not set';
                }

                $formattedReservations[] = $reservation;
            }

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

    public static function markAsPickedUp($id, $admin_notes = '')
    {
        try {
            // Simple update - only change status to picked_up
            $stmt = self::conn()->prepare('
                UPDATE reservations 
                SET status = "picked_up", 
                    updated_at = NOW() 
                WHERE id = ?
            ');
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                return [
                    'success' => true,
                    'message' => 'Product marked as picked up successfully.',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Reservation not found.',
                ];
            }
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to update pickup status: ' . $e->getMessage(),
            ];
        }
    }

    public static function getPickedUpByUser($user_uuid)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT 
                    r.*,
                    r.id as reservation_id,
                    r.products,
                    r.updated_at as pickup_date
                FROM reservations r
                WHERE r.user_uuid = ? AND r.status = "picked_up"
                ORDER BY r.updated_at DESC
            ');
            $stmt->execute([$user_uuid]);
            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format data safely
            $formattedReservations = [];
            foreach ($reservations as $reservation) {
                // Safely decode JSON
                $products = [];
                if (!empty($reservation['products'])) {
                    $decoded = json_decode($reservation['products'], true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $products = $decoded;
                    }
                }

                $reservation['products_array'] = $products;
                $reservation['products_count'] = count($products);

                // Safe date formatting - use updated_at as pickup date
                if (!empty($reservation['pickup_date'])) {
                    $reservation['formatted_pickup_date'] = date('F j, Y g:i A', strtotime($reservation['pickup_date']));
                } else {
                    $reservation['formatted_pickup_date'] = 'Pickup date not set';
                }

                $formattedReservations[] = $reservation;
            }

            return [
                'success' => true,
                'data' => $formattedReservations,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to fetch picked up reservations: ' . $e->getMessage(),
            ];
        }
    }
}
