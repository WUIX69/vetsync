<?php

namespace VetSync\Models;

use PDO;
use PDOException;

class Attachments
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function all($reference_uuid)
    {
        try {
            $sql = "SELECT * FROM attachments WHERE reference_uuid = :reference_uuid";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':reference_uuid' => $reference_uuid]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
            return [
                'success' => true,
                'message' => 'Attachments fetched successfully',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to fetch attachments: ' . $e->getMessage(),
            ];
        }
    }

    public function single($reference_uuid)
    {
        try {
            $sql = "SELECT * FROM attachments WHERE reference_uuid = :reference_uuid LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':reference_uuid' => $reference_uuid]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
            return [
                'success' => true,
                'message' => 'Attachment fetched successfully',
                'data' => $data,
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to fetch attachment: ' . $e->getMessage(),
            ];
        }
    }

    public function store($data = [])
    {
        try {
            $this->conn->beginTransaction();
            $sql = "INSERT INTO attachments (
                        reference_model, 
                        reference_uuid, 
                        folder, 
                        filename
                    ) VALUES (
                        :reference_model, 
                        :reference_uuid, 
                        :folder, 
                        :filename
                    )";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':reference_model' => $data['reference_model'],
                ':reference_uuid' => $data['reference_uuid'],
                ':folder' => $data['folder'],
                ':filename' => $data['filename']
            ]);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => 'Attachment stored successfully',
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Failed to store attachment: ' . $e->getMessage(),
            ];
        }
    }

    public function update($reference_model, $reference_uuid, $data = [])
    {
        try {
            $this->conn->beginTransaction();
            $sql = "UPDATE attachments SET 
                        folder = :folder, 
                        filename = :filename
                    WHERE reference_model = :reference_model 
                    AND reference_uuid = :reference_uuid";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':folder' => $data['folder'],
                ':filename' => $data['filename'],
                ':reference_model' => $reference_model,
                ':reference_uuid' => $reference_uuid
            ]);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => 'Attachment updated successfully',
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Failed to update attachment: ' . $e->getMessage(),
            ];
        }
    }

    public function deleteWhereReference($reference_model, $reference_uuid)
    {
        try {
            $this->conn->beginTransaction();
            $sql = "DELETE FROM attachments 
                    WHERE reference_model = :reference_model 
                    AND reference_uuid = :reference_uuid";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':reference_model' => $reference_model,
                ':reference_uuid' => $reference_uuid
            ]);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => 'Attachment deleted successfully',
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Failed to delete attachment: ' . $e->getMessage(),
            ];
        }
    }

    public function deleteWhereFolder($folder = null)
    {
        try {
            $this->conn->beginTransaction();

            $sql = "DELETE FROM attachments WHERE folder = :folder";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':folder' => $folder]);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => 'Successfully deleted attachment',
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Failed to delete attachment: ' . $e->getMessage(),
            ];
        }
    }
}