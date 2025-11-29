<?php

namespace App\Models;

use Core\Model;

class Member extends Model
{
    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';

    public function getAllMembers()
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
    }

    public function getPaginatedMembers($limit, $offset)
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        return $this->db->query($sql, ['limit' => $limit, 'offset' => $offset]);
    }

    public function countAllMembers()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->query($sql);
        return $result[0]['total'] ?? 0;
    }

    public function getMemberById($id)
    {
        $result = $this->db->query("SELECT * FROM {$this->table} WHERE id_anggota = :id", ['id' => $id]);
        return $result[0] ?? null;
    }

    public function createMember($data)
    {
        $sql = "INSERT INTO {$this->table} (username, password, nama_lengkap, nip_nim, role, foto_profil, status_aktif) 
                VALUES (:username, :password, :nama_lengkap, :nip_nim, :role, :foto_profil, :status_aktif)";

        return $this->db->execute($sql, [
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'nama_lengkap' => $data['nama_lengkap'],
            'nip_nim' => $data['nip_nim'] ?? null,
            'role' => $data['role'] ?? 'operator',
            'foto_profil' => $data['foto_profil'] ?? null,
            'status_aktif' => $data['status_aktif'] ?? true
        ]);
    }

    public function updateMember($id, $data)
    {
        $fields = [];
        $params = ['id' => $id];

        if (isset($data['username'])) {
            $fields[] = "username = :username";
            $params['username'] = $data['username'];
        }
        if (isset($data['password']) && !empty($data['password'])) {
            $fields[] = "password = :password";
            $params['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        if (isset($data['nama_lengkap'])) {
            $fields[] = "nama_lengkap = :nama_lengkap";
            $params['nama_lengkap'] = $data['nama_lengkap'];
        }
        if (isset($data['nip_nim'])) {
            $fields[] = "nip_nim = :nip_nim";
            $params['nip_nim'] = $data['nip_nim'];
        }
        if (isset($data['role'])) {
            $fields[] = "role = :role";
            $params['role'] = $data['role'];
        }
        if (isset($data['foto_profil'])) {
            $fields[] = "foto_profil = :foto_profil";
            $params['foto_profil'] = $data['foto_profil'];
        }
        if (isset($data['status_aktif'])) {
            $fields[] = "status_aktif = :status_aktif";
            $params['status_aktif'] = $data['status_aktif'];
        }

        $fields[] = "updated_at = CURRENT_TIMESTAMP";

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id_anggota = :id";
        return $this->db->execute($sql, $params);
    }

    public function deleteMember($id)
    {
        return $this->db->execute("DELETE FROM {$this->table} WHERE id_anggota = :id", ['id' => $id]);
    }

    public function authenticate($username, $password)
    {
        $user = $this->db->query("SELECT * FROM {$this->table} WHERE username = :username", ['username' => $username])[0] ?? null;

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }
}
