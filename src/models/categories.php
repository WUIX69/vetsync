<?php

namespace VetSync\Models;

use PDO;
use PDOException;

class Categories
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function all($reference_model = null)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM categories WHERE reference_model=?');
            $stmt->execute([$reference_model]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
            return [
                'success' => true,
                'message' => $reference_model . ' categories fetched successfully',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $reference_model . ' categories fetch failed: ' . $e->getMessage(),
            ];
        }
    }

    public function single($id = null, $reference_model = null)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM categories WHERE id=? AND reference_model=? LIMIT 1');
            $stmt->execute([$id, $reference_model]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
            return [
                'success' => true,
                'message' => $reference_model . ' category fetched successfully',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $reference_model . ' category fetch failed: ' . $e->getMessage(),
            ];
        }
    }

    public function store($data = [])
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("
                INSERT INTO categories (
                    reference_model, 
                    icon, 
                    name, 
                    description, 
                    status
                ) VALUES (
                    ?, ?, ?, ?, ?
                )
            ");

            $stmt->execute([
                $data['reference_model'],
                $data['icon'],
                $data['name'],
                $data['description'],
                $data['status']
            ]);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => $data['reference_model'] . ' category created successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => $data['reference_model'] . ' category creation failed: ' . $e->getMessage(),
            ];
        }
    }

    public function update($data = [])
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("
                UPDATE categories SET 
                    icon=?, 
                    name=?, 
                    description=?, 
                    status=?
                WHERE id=? AND reference_model=?
            ");

            $stmt->execute([
                $data['icon'],
                $data['name'],
                $data['description'],
                $data['status'],
                $data['id'],
                $data['reference_model']
            ]);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => $data['reference_model'] . ' category updated successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => $data['reference_model'] . ' category update failed: ' . $e->getMessage(),
            ];
        }
    }

    public function delete($id = null, $reference_model = null)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare('DELETE FROM categories WHERE id=? AND reference_model=?');
            $stmt->execute([$id, $reference_model]);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => $reference_model . ' category deleted successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => $reference_model . ' category deletion failed: ' . $e->getMessage(),
            ];
        }
    }
}