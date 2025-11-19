<?php

namespace App\Models;

use Core\Model;

/**
 * User Model
 * Simple model with clear database queries
 */
class User extends Model
{
    /**
     * Get all users from database
     */
    public function getAllUsers()
    {
        $sql = "SELECT * FROM users";
        return $this->db->query($sql);
    }

    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);

        return $result[0] ?? null;
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $result = $this->db->query($sql, ['email' => $email]);

        return $result[0] ?? null;
    }

    /**
     * Create new user
     */
    public function createUser($name, $email, $password)
    {
        $sql = "INSERT INTO users (name, email, password, created_at)
                VALUES (:name, :email, :password, :created_at)";

        $this->db->execute($sql, [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Update user
     */
    public function updateUser($id, $name, $email)
    {
        $sql = "UPDATE users
                SET name = :name, email = :email, updated_at = :updated_at
                WHERE id = :id";

        return $this->db->execute($sql, [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }

    /**
     * Get active users
     */
    public function getActiveUsers()
    {
        $sql = "SELECT * FROM users WHERE status = :status";
        return $this->db->query($sql, ['status' => 'active']);
    }
}
