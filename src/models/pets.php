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

    public static function all($user_uuid = null, $archive_status = 'active')
    {
        try {
            // Auto-archive inactive pets before fetching active pets
            if ($archive_status === 'active') {
                self::autoArchiveInactivePets($user_uuid);
            }

            if ($user_uuid) {
                $stmt = self::conn()->prepare('SELECT * FROM pets WHERE user_uuid = ? AND archive_status = ?');
                $stmt->execute([$user_uuid, $archive_status]);
            } else {
                $stmt = self::conn()->prepare('SELECT * FROM pets WHERE archive_status = ?');
                $stmt->execute([$archive_status]);
            }
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
                    user_uuid, 
                    name, 
                    dob, 
                    species,
                    breed 
                ) VALUES (
                    ?, ?, ?, ?, ?, ?
                )
            ");

            $stmt->execute([
                $data['uuid'],
                $data['user_uuid'],
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

            // Check for existing appointments
            $appointmentCheck = \VetSync\Models\Appointments::getByPetUuid($uuid);
            if ($appointmentCheck['success'] && $appointmentCheck['data']['appointment_count'] > 0) {
                self::conn()->rollBack();
                return [
                    'success' => false,
                    'message' => 'Cannot delete pet with active appointments. Please cancel or complete appointments first.',
                ];
            }

            $stmt = self::conn()->prepare('DELETE FROM pets WHERE uuid=?');
            $stmt->execute([$uuid]);

            self::conn()->commit();
            return [
                'success' => true,
                'message' => 'Pet deleted successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            self::conn()->rollBack();
            return [
                'success' => false,
                'message' => 'Pet deletion failed: ' . $e->getMessage(),
            ];
        }
    }

    public static function archive($uuid, $archive_status = 'inactive')
    {
        try {
            self::conn()->beginTransaction();

            $stmt = self::conn()->prepare("
                UPDATE pets SET 
                    archive_status = ?,
                    archived_at = NOW()
                WHERE uuid = ?
            ");

            $stmt->execute([$archive_status, $uuid]);

            self::conn()->commit();
            return [
                'success' => true,
                'message' => 'Pet archived successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            self::conn()->rollBack();
            return [
                'success' => false,
                'message' => 'Pet archiving failed: ' . $e->getMessage(),
            ];
        }
    }

    public static function unarchive($uuid)
    {
        try {
            self::conn()->beginTransaction();

            $stmt = self::conn()->prepare("
                UPDATE pets SET 
                    archive_status = 'active',
                    archived_at = NULL
                WHERE uuid = ?
            ");

            $stmt->execute([$uuid]);

            self::conn()->commit();
            return [
                'success' => true,
                'message' => 'Pet restored successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            self::conn()->rollBack();
            return [
                'success' => false,
                'message' => 'Pet restoration failed: ' . $e->getMessage(),
            ];
        }
    }

    public static function getInactivePets($user_uuid = null)
    {
        try {
            // Get pets that haven't had appointments in the last year
            $sql = "
                SELECT DISTINCT p.* 
                FROM pets p
                LEFT JOIN appointments a ON p.uuid = a.pet_uuid 
                    AND a.date >= DATE_SUB(NOW(), INTERVAL 1 YEAR)
                    AND a.status IN ('completed', 'accepted')
                WHERE p.archive_status = 'active'
                AND a.pet_uuid IS NULL
            ";

            if ($user_uuid) {
                $sql .= " AND p.user_uuid = ?";
                $stmt = self::conn()->prepare($sql);
                $stmt->execute([$user_uuid]);
            } else {
                $stmt = self::conn()->prepare($sql);
                $stmt->execute();
            }

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
            return [
                'success' => true,
                'message' => 'Inactive pets fetched successfully.',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Inactive pets fetching failed: ' . $e->getMessage(),
            ];
        }
    }

    public static function getAllArchived($user_uuid = null)
    {
        try {
            if ($user_uuid) {
                $stmt = self::conn()->prepare('SELECT * FROM pets WHERE user_uuid = ? AND archive_status IN ("inactive", "deceased")');
                $stmt->execute([$user_uuid]);
            } else {
                $stmt = self::conn()->prepare('SELECT * FROM pets WHERE archive_status IN ("inactive", "deceased")');
                $stmt->execute();
            }
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
            return [
                'success' => true,
                'message' => 'Archived pets fetched successfully.',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Archived pets fetching failed: ' . $e->getMessage(),
            ];
        }
    }

    // Add this method to auto-archive only inactive pets (not deceased)
    public static function autoArchiveInactivePets($user_uuid = null)
    {
        try {
            self::conn()->beginTransaction();

            // PRODUCTION VERSION - Archives pets after 1 year without appointments
            $sql = "
                UPDATE pets p 
                SET archive_status = 'inactive', archived_at = NOW()
                WHERE p.archive_status = 'active'
                AND p.uuid NOT IN (
                    SELECT DISTINCT a.pet_uuid 
                    FROM appointments a 
                    WHERE a.pet_uuid = p.uuid 
                    AND a.date >= DATE_SUB(NOW(), INTERVAL 1 YEAR)
                    AND a.status IN ('completed', 'accepted')
                )
                AND p.created_at <= DATE_SUB(NOW(), INTERVAL 1 YEAR)
            ";

            if ($user_uuid) {
                $sql .= " AND p.user_uuid = ?";
                $stmt = self::conn()->prepare($sql);
                $stmt->execute([$user_uuid]);
            } else {
                $stmt = self::conn()->prepare($sql);
                $stmt->execute();
            }

            $archivedCount = $stmt->rowCount();

            self::conn()->commit();
            return [
                'success' => true,
                'message' => "Automatically archived {$archivedCount} inactive pets.",
                'archived_count' => $archivedCount,
            ];
        } catch (PDOException $e) {
            error_log("SQL Error in autoArchiveInactivePets: " . $e->getMessage());
            self::conn()->rollBack();
            return [
                'success' => false,
                'message' => 'Auto-archiving failed: ' . $e->getMessage(),
            ];
        }
    }
}