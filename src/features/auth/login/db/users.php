<?php

class UsersDB
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function single($uuid = null)
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE uuid=? LIMIT 1');
        $stmt->execute([$uuid]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
    }

    public function singleWhereUserEmail($email = null)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
    }

    public function singleWhereAdminEmail($email = null)
    {
        // Implement your admin query here, for now just return empty array
        return [];
    }
}