<?php

namespace VetSync\Models;

use PDO;
use PDOException;

class Pets
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
            $stmt = self::conn()->prepare('SELECT * FROM pets');
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
            return [
                'success' => true,
                'message' => 'Pets fetched successfully.',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Pets fetching failed: ' . $e->getMessage(),
            ];
        }
    }

    public static function single($uuid = null)
    {
        try {
            $stmt = self::conn()->prepare('SELECT * FROM pets WHERE uuid=? LIMIT 1');
            $stmt->execute([$uuid]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
            return [
                'success' => true,
                'message' => 'Pets fetched successfully.',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Pets fetching failed: ' . $e->getMessage(),
            ];
        }
    }

    public static function store($data = [])
    {
        try {
            self::conn()->beginTransaction();

            $stmt = self::conn()->prepare("
                INSERT INTO pets (
                    uuid, 
                    name, 
                    dob, 
                    species,
                    breed 
                ) VALUES (
                    ?, ?, ?, ?, ?
                )
            ");

            $stmt->execute([
                $data['uuid'],
                $data['name'],
                $data['dob'],
                $data['species'],
                $data['breed']
            ]);

            self::conn()->commit();
            return [
                'success' => true,
                'message' => 'Pets created successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            self::conn()->rollBack();
            return [
                'success' => false,
                'message' => 'Pets creation failed: ' . $e->getMessage(),
            ];
        }
    }

    public static function update($data = [])
    {
        try {
            self::conn()->beginTransaction();

            // Only update the fields that are present in $data
            // For now, update name, dob, species, breed (no category_id)
            $stmt = self::conn()->prepare("
                UPDATE pets SET 
                    name = ?, 
                    dob = ?, 
                    species = ?,
                    breed = ?
                WHERE uuid = ?
            ");

            $stmt->execute([
                $data['name'],
                $data['dob'],
                $data['species'],
                $data['breed'],
                $data['uuid']
            ]);

            self::conn()->commit();
            return [
                'success' => true,
                'message' => 'Pets updated successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            self::conn()->rollBack();
            return [
                'success' => false,
                'message' => 'Pets update failed: ' . $e->getMessage(),
            ];
        }
    }

    public static function delete($uuid = null)
    {
        try {
            self::conn()->beginTransaction();

            $stmt = self::conn()->prepare('DELETE FROM pets WHERE uuid=?');
            $stmt->execute([$uuid]);

            self::conn()->commit();
            return [
                'success' => true,
                'message' => 'Pets deleted successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            self::conn()->rollBack();
            return [
                'success' => false,
                'message' => 'Pets deletion failed: ' . $e->getMessage(),
            ];
        }
    }
}