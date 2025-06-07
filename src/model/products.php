<?php

namespace VetSync\Model;

use PDO;
use PDOException;

class Products
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function all()
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM products');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [];
        }
    }

    public function single($uuid = null)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM products WHERE uuid=? LIMIT 1');
            $stmt->execute([$uuid]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [];
        }
    }

    public function store($data = [])
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("
                INSERT INTO products (
                    uuid, 
                    name, 
                    description, 
                    price, 
                    quantity
                ) VALUES (
                    ?, ?, ?, ?, ?
                )
            ");

            $stmt->execute([
                $data['uuid'],
                $data['name'],
                $data['description'],
                $data['price'],
                $data['quantity']
            ]);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => 'Product created successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Product creation failed: ' . $e->getMessage(),
            ];
        }
    }

    public function update($data = [])
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("
                UPDATE products SET 
                    name=?, 
                    description=?, 
                    price=?, 
                    quantity=?,
                    updated_at=NOW()
                WHERE uuid=?
            ");

            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['price'],
                $data['quantity'],
                $data['uuid']
            ]);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => 'Product updated successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            $this->conn->rollBack();
            return 0;
        }
    }

    public function delete($uuid = null)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare('DELETE FROM products WHERE uuid=?');
            $stmt->execute([$uuid]);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => 'Product deleted successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Product deletion failed: ' . $e->getMessage(),
            ];
        }
    }
}