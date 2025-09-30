<?php

namespace VetSync\Models;

use PDO;
use PDOException;
use Exception;
use DateTime; // Added for date formatting

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

    public static function all()
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT 
                    r.*, 
                    CONCAT(u.firstname, " ", u.lastname) AS user_name,
                    u.email AS user_email,
                    u.telephone AS user_phone
                FROM reservations r
                LEFT JOIN users u ON r.user_uuid = u.uuid
                ORDER BY r.created_at DESC
            ');
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

            // Process each reservation
            foreach ($data as &$reservation) {
                // ✅ SIMPLE: Use the SAME method as users table - media() function
                $avatarUrl = null;
                if (function_exists('media') && !empty($reservation['user_uuid'])) {
                    $avatarUrl = media($reservation['user_uuid']);
                }

                // If no profile image or media function doesn't work, use colorful avatar
                if (!$avatarUrl || $avatarUrl === '/public/img/profiles/' || strpos($avatarUrl, 'placeholders') !== false) {
                    $fullName = $reservation['user_name'] ?: 'User';
                    $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($fullName) .
                        "&size=150&background=random&color=fff&font-size=0.6";
                }

                $reservation['profile_image'] = $avatarUrl;

                // ✅ FIX: Format dates properly
                if (!empty($reservation['preferred_date'])) {
                    try {
                        $date = new DateTime($reservation['preferred_date']);
                        $reservation['formatted_date'] = $date->format('M j, Y');
                    } catch (Exception $e) {
                        $reservation['formatted_date'] = 'No date';
                    }
                } else {
                    $reservation['formatted_date'] = 'No date';
                }

                // ✅ FIX: Format time properly
                if (!empty($reservation['preferred_time'])) {
                    try {
                        $time = new DateTime($reservation['preferred_time']);
                        $reservation['formatted_time'] = $time->format('g:i A');
                    } catch (Exception $e) {
                        if (preg_match('/^\d{2}:\d{2}$/', $reservation['preferred_time'])) {
                            $time = DateTime::createFromFormat('H:i', $reservation['preferred_time']);
                            $reservation['formatted_time'] = $time ? $time->format('g:i A') : 'No time';
                        } else {
                            $reservation['formatted_time'] = 'No time';
                        }
                    }
                } else {
                    $reservation['formatted_time'] = 'No time';
                }

                // Process products JSON
                if (!empty($reservation['products'])) {
                    $products = json_decode($reservation['products'], true);
                    $reservation['products_array'] = $products ?? [];
                    $reservation['products_count'] = is_array($products) ? count($products) : 0;
                } else {
                    $reservation['products_array'] = [];
                    $reservation['products_count'] = 0;
                }
            }

            return [
                'success' => true,
                'message' => 'Reservations fetched successfully.',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to fetch reservations: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }

    public static function single($id)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT 
                    r.*, 
                    CONCAT(u.firstname, " ", u.lastname) AS user_name,
                    u.email AS user_email,
                    u.telephone AS user_phone
                FROM reservations r
                LEFT JOIN users u ON r.user_uuid = u.uuid
                WHERE r.id = ?
            ');
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                // Process the products JSON
                if (!empty($data['products'])) {
                    $products = json_decode($data['products'], true);
                    $data['products_array'] = $products ?? [];
                } else {
                    $data['products_array'] = [];
                }

                return [
                    'success' => true,
                    'message' => 'Reservation fetched successfully.',
                    'data' => $data,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Reservation not found.',
                    'data' => null,
                ];
            }
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to fetch reservation: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public static function store($data)
    {
        try {
            $stmt = self::conn()->prepare('
                INSERT INTO reservations (
                    user_uuid, products, preferred_date, preferred_time,
                    delivery_method, notes, total_amount, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ');

            $stmt->execute([
                $data['user_uuid'],
                $data['products'], // Already JSON encoded
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
                'data' => ['id' => self::conn()->lastInsertId()],
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to create reservation: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public static function update($id, $data)
    {
        try {
            $updateFields = [];
            $params = [];

            if (isset($data['status'])) {
                $updateFields[] = 'status = ?';
                $params[] = $data['status'];
            }
            if (isset($data['rejection_reason'])) {
                $updateFields[] = 'rejection_reason = ?';
                $params[] = $data['rejection_reason'];
            }
            if (isset($data['delivery_method'])) {
                $updateFields[] = 'delivery_method = ?';
                $params[] = $data['delivery_method'];
            }
            if (isset($data['notes'])) {
                $updateFields[] = 'notes = ?';
                $params[] = $data['notes'];
            }

            $updateFields[] = 'updated_at = NOW()';
            $params[] = $id;

            $sql = 'UPDATE reservations SET ' . implode(', ', $updateFields) . ' WHERE id = ?';
            $stmt = self::conn()->prepare($sql);
            $stmt->execute($params);

            if ($stmt->rowCount() > 0) {
                return [
                    'success' => true,
                    'message' => 'Reservation updated successfully.',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No changes made or reservation not found.',
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

    public static function updateStatus($id, $status, $reason = '', $isNoShow = false)
    {
        try {
            // Get reservation details for email notification and user health update
            $reservationStmt = self::conn()->prepare('
                SELECT 
                    r.*, 
                    CONCAT(u.firstname, " ", u.lastname) AS user_name,
                    u.email AS user_email,
                    u.user_health AS current_health
                FROM reservations r
                LEFT JOIN users u ON r.user_uuid = u.uuid
                WHERE r.id = ?
            ');
            $reservationStmt->execute([$id]);
            $reservationData = $reservationStmt->fetch(PDO::FETCH_ASSOC);

            // Update reservation status
            $stmt = self::conn()->prepare('
                UPDATE reservations 
                SET status = ?, 
                    rejection_reason = ?,
                    updated_at = NOW() 
                WHERE id = ?
            ');
            $stmt->execute([$status, $reason, $id]);

            if ($stmt->rowCount() > 0) {
                // Handle no-show penalty
                if ($isNoShow && $reservationData && $reservationData['user_uuid']) {
                    $currentHealth = floatval($reservationData['current_health'] ?? 100);
                    $newHealth = max(0, $currentHealth - 20); // Reduce by 20%, minimum 0%
                    
                    $healthStmt = self::conn()->prepare('
                        UPDATE users 
                        SET user_health = ? 
                        WHERE uuid = ?
                    ');
                    $healthStmt->execute([$newHealth, $reservationData['user_uuid']]);
                    
                    error_log("User health penalty applied: {$reservationData['user_email']} health reduced from {$currentHealth}% to {$newHealth}%");
                }

                // Send email notification when reservation is ready for pickup
                if ($status === 'ready_for_pickup' && $reservationData && $reservationData['user_email']) {
                    try {
                        $emailService = new \VetSync\Services\Email();

                        // Parse products for email
                        $productNames = '';
                        if (!empty($reservationData['products'])) {
                            $products = json_decode($reservationData['products'], true);
                            if (is_array($products)) {
                                $productList = [];
                                foreach ($products as $product) {
                                    $name = $product['name'] ?? 'Product';
                                    $qty = $product['qty'] ?? 1;
                                    $productList[] = "• {$name} (Qty: {$qty})";
                                }
                                $productNames = implode('<br>', $productList);
                            }
                        }

                        $emailResult = $emailService->sendPickupNotification(
                            $reservationData['user_email'],
                            $reservationData['user_name'],
                            $productNames,
                            $reservationData['created_at'], // Using created_at as reservation date
                            $reservationData['total_amount'] ?? 0
                        );

                        if (!$emailResult['success']) {
                            error_log("Failed to send pickup notification email: " . $emailResult['message']);
                        }
                    } catch (Exception $e) {
                        error_log("Pickup notification email error: " . $e->getMessage());
                        // Don't fail the status update if email fails
                    }
                }

                return [
                    'success' => true,
                    'message' => 'Reservation updated successfully.',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No changes made or reservation not found.',
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

    public static function delete($id)
    {
        try {
            $stmt = self::conn()->prepare('DELETE FROM reservations WHERE id = ?');
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                return [
                    'success' => true,
                    'message' => 'Reservation deleted successfully.',
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
                'message' => 'Failed to delete reservation: ' . $e->getMessage(),
            ];
        }
    }

    public static function getByUser($userUuid)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT r.* 
                FROM reservations r
                WHERE r.user_uuid = ?
                ORDER BY r.created_at DESC
            ');
            $stmt->execute([$userUuid]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

            // Process the products JSON for each reservation
            foreach ($data as &$reservation) {
                if (!empty($reservation['products'])) {
                    $products = json_decode($reservation['products'], true);
                    $reservation['products_array'] = $products ?? [];
                } else {
                    $reservation['products_array'] = [];
                }
            }

            return [
                'success' => true,
                'message' => 'User reservations fetched successfully.',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to fetch user reservations: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }

    // 🔥 UPDATED METHOD WITH CORRECT JSON STRUCTURE HANDLING
    public static function markAsPickedUp($id, $admin_notes = '')
    {
        try {
            self::conn()->beginTransaction();

            // Get the reservation
            $stmt = self::conn()->prepare('SELECT products, status FROM reservations WHERE id = ?');
            $stmt->execute([$id]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$reservation) {
                self::conn()->rollBack();
                return ['success' => false, 'message' => 'Reservation not found.'];
            }

            // ✅ SIMPLE: Allow any status except already picked_up
            if ($reservation['status'] === 'picked_up') {
                self::conn()->rollBack();
                return ['success' => false, 'message' => 'Reservation already marked as picked up.'];
            }

            // Parse products and reduce stock
            $products = json_decode($reservation['products'], true);
            if (!is_array($products)) {
                self::conn()->rollBack();
                return ['success' => false, 'message' => 'Invalid products data.'];
            }

            $reducedProducts = [];

            foreach ($products as $product) {
                $productUuid = $product['product_uuid'] ?? null;
                $quantity = intval($product['qty'] ?? 0);

                if ($productUuid && $quantity > 0) {
                    // Reduce stock
                    $updateStock = self::conn()->prepare('UPDATE products SET stock = stock - ? WHERE uuid = ? AND stock >= ?');
                    $updateStock->execute([$quantity, $productUuid, $quantity]);

                    if ($updateStock->rowCount() > 0) {
                        $reducedProducts[] = ($product['name'] ?? 'Product') . " (Qty: $quantity)";
                    }
                }
            }

            // Update reservation status
            $updateStmt = self::conn()->prepare('UPDATE reservations SET status = "picked_up", notes = ?, updated_at = NOW() WHERE id = ?');
            $updateStmt->execute([$admin_notes, $id]);

            self::conn()->commit();

            return [
                'success' => true,
                'message' => 'Marked as picked up successfully! Stock reduced for: ' . implode(', ', $reducedProducts)
            ];

        } catch (Exception $e) {
            self::conn()->rollBack();
            error_log("Stock reduction error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to mark as picked up: ' . $e->getMessage()];
        }
    }

    public static function getPickedUpByUser($user_uuid)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT 
                    r.id,
                    r.products,
                    r.total_amount,
                    r.status,
                    r.created_at,
                    r.updated_at as pickup_date
                FROM reservations r
                WHERE r.user_uuid = ? AND r.status = "picked_up"
                ORDER BY r.updated_at DESC
            ');
            $stmt->execute([$user_uuid]);
            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

            // Format dates and parse products
            foreach ($reservations as &$reservation) {
                // Format pickup date
                if (!empty($reservation['pickup_date'])) {
                    $reservation['formatted_pickup_date'] = date('F j, Y g:i A', strtotime($reservation['pickup_date']));
                } else {
                    $reservation['formatted_pickup_date'] = 'Pickup date not set';
                }

                // Parse products JSON
                if (!empty($reservation['products'])) {
                    $products = json_decode($reservation['products'], true);
                    $reservation['products_array'] = $products ?? [];
                } else {
                    $reservation['products_array'] = [];
                }
            }

            return [
                'success' => true,
                'message' => 'Picked up reservations fetched successfully.',
                'data' => $reservations,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to fetch picked up reservations: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }

    public static function getReadyForPickupByUser($user_uuid)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT 
                    r.id,
                    r.products,
                    r.total_amount,
                    r.status,
                    r.created_at,
                    r.updated_at
                FROM reservations r
                WHERE r.user_uuid = ? AND r.status = "ready_for_pickup"
                ORDER BY r.updated_at DESC
            ');
            $stmt->execute([$user_uuid]);
            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

            // Parse products JSON
            foreach ($reservations as &$reservation) {
                if (!empty($reservation['products'])) {
                    $products = json_decode($reservation['products'], true);
                    $reservation['products_array'] = $products ?? [];
                } else {
                    $reservation['products_array'] = [];
                }
            }

            return [
                'success' => true,
                'message' => 'Ready for pickup reservations fetched successfully.',
                'data' => $reservations,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to fetch ready for pickup reservations: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }
}
