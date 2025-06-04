<?php

namespace VetSync\Model;

use PDO;
use PDOException;

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
        $admin = [
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => '$2y$10$xKIlO8qhCbD5hxi466mlHupG5f8LkDakJia8T90kbwsBpS/RjNhg2' // admin
        ];

        return ($email === $admin['email']) ? $admin : [];
    }

    function store($data = [])
    {
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare("
                INSERT INTO users (
                    uuid, firstname, lastname, email, password
                ) VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $data['uuid'],
                $data['firstname'],
                $data['lastname'],
                $data['email'],
                $data['password']
            ]);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => 'User registered successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'User registration failed.',
            ];
        }
    }

    function update($data = [])
    {
        try {

            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare("
                UPDATE users SET 
                    firstname=?, 
                    lastname=?, 
                    email=?, 
                    bio=?, 
                    telephone=?, 
                    dob=?, 
                    location=?,
                    urls=?
                WHERE uuid=?
            ");

            $stmt->execute([
                $data['firstname'],
                $data['lastname'],
                $data['email'],
                $data['bio'],
                $data['telephone'],
                $data['dob'],
                $data['location'],
                $data['urls'],
                $data['user_uuid']
            ]);

            $this->conn->commit();
            return [
                'success' => true,
                'message' => 'User updated successfully.',
            ];
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'User update failed.',
            ];
        }
    }
}