<?php

namespace VetSync\Services;

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

    public function single($reference_uuid)
    {
        $sql = "SELECT * FROM attachments WHERE reference_uuid = :reference_uuid LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':reference_uuid', $reference_uuid);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
    }

    public function store($data = [])
    {
        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO attachments (reference_model, reference_uuid, folder, filename) VALUES (:reference_model, :reference_uuid, :folder, :filename)";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':reference_model', $data['reference_model']);
            $stmt->bindParam(':reference_uuid', $data['reference_uuid']);
            $stmt->bindParam(':folder', $data['folder']);
            $stmt->bindParam(':filename', $data['filename']);
            $stmt->execute();

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

    public function delete($reference_model, $reference_uuid)
    {
        try {
            $this->conn->beginTransaction();

            $sql = "DELETE FROM attachments WHERE reference_model = :reference_model AND reference_uuid = :reference_uuid";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':reference_model', $reference_model);
            $stmt->bindParam(':reference_uuid', $reference_uuid);
            $stmt->execute();

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
}