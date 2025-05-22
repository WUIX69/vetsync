<?php

class Users
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function single($uuid = null)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM users WHERE uuid=? LIMIT 1');
            $stmt->execute([$uuid]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [];
        }
    }

    public function singleWhereUserEmail($email = null)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [];
        }
    }

    public function singleWhereAdminEmail($email = null)
    {
        // Implement your admin query here, for now just return empty array
        return [];
    }
}