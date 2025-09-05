<?php

namespace VetSync\Models;

use PDO;
use PDOException;

class Cart
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

    public static function add($user_uuid, $product_uuid, $qty = 1, $size = 'm', $total_price = 0)
    {
        try {
            // Check if item already exists in cart with same size
            $stmt = self::conn()->prepare('SELECT * FROM carts WHERE user_uuid = ? AND product_uuid = ? AND size = ?');
            $stmt->execute([$user_uuid, $product_uuid, $size]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Update quantity and total price
                $newQty = $existing['qty'] + $qty;
                $newTotalPrice = $existing['total_price'] + $total_price;

                $stmt = self::conn()->prepare('UPDATE carts SET qty = ?, total_price = ? WHERE user_uuid = ? AND product_uuid = ? AND size = ?');
                $stmt->execute([$newQty, $newTotalPrice, $user_uuid, $product_uuid, $size]);
            } else {
                // Insert new item - Generate manual ID to avoid AUTO_INCREMENT issues
                $id = self::generateUniqueId();
                $stmt = self::conn()->prepare('INSERT INTO carts (id, user_uuid, product_uuid, qty, size, total_price, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
                $stmt->execute([$id, $user_uuid, $product_uuid, $qty, $size, $total_price]);
            }

            return [
                'success' => true,
                'message' => 'Product added to cart successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to add product to cart: ' . $e->getMessage(),
            ];
        }
    }

    // Generate unique ID for cart
    private static function generateUniqueId()
    {
        try {
            $stmt = self::conn()->prepare('SELECT COALESCE(MAX(id), 0) + 1 as next_id FROM carts');
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['next_id'];
        } catch (PDOException $e) {
            // Fallback to timestamp-based ID (smaller version)
            return intval((time() % 1000000) . rand(10, 99));
        }
    }

    public static function getItems($user_uuid)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT c.*, p.name, p.og_price, p.dc_price, p.stock
                FROM carts c 
                JOIN products p ON c.product_uuid = p.uuid 
                WHERE c.user_uuid = ?
                ORDER BY c.created_at DESC
            ');
            $stmt->execute([$user_uuid]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

            // Add image for each product
            foreach ($data as &$item) {
                $item['image'] = media($item['product_uuid']);
            }

            return [
                'success' => true,
                'message' => 'Cart items fetched successfully.',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to fetch cart items: ' . $e->getMessage(),
            ];
        }
    }

    public static function getCount($user_uuid)
    {
        try {
            $stmt = self::conn()->prepare('SELECT SUM(qty) as count FROM carts WHERE user_uuid = ?');
            $stmt->execute([$user_uuid]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'success' => true,
                'count' => intval($result['count'] ?? 0),
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'count' => 0,
            ];
        }
    }

    public static function updateQuantity($user_uuid, $product_uuid, $size, $qty, $total_price)
    {
        try {
            $stmt = self::conn()->prepare('UPDATE carts SET qty = ?, total_price = ? WHERE user_uuid = ? AND product_uuid = ? AND size = ?');
            $stmt->execute([$qty, $total_price, $user_uuid, $product_uuid, $size]);

            return [
                'success' => true,
                'message' => 'Cart updated successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to update cart: ' . $e->getMessage(),
            ];
        }
    }

    public static function remove($user_uuid, $product_uuid, $size = null)
    {
        try {
            if ($size) {
                $stmt = self::conn()->prepare('DELETE FROM carts WHERE user_uuid = ? AND product_uuid = ? AND size = ?');
                $stmt->execute([$user_uuid, $product_uuid, $size]);
            } else {
                $stmt = self::conn()->prepare('DELETE FROM carts WHERE user_uuid = ? AND product_uuid = ?');
                $stmt->execute([$user_uuid, $product_uuid]);
            }

            return [
                'success' => true,
                'message' => 'Product removed from cart successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to remove product from cart: ' . $e->getMessage(),
            ];
        }
    }

    public static function clear($user_uuid)
    {
        try {
            $stmt = self::conn()->prepare('DELETE FROM carts WHERE user_uuid = ?');
            $stmt->execute([$user_uuid]);

            return [
                'success' => true,
                'message' => 'Cart cleared successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to clear cart: ' . $e->getMessage(),
            ];
        }
    }
}
