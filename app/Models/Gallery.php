<?php

namespace App\Models;

use Core\Model;

class Gallery extends Model
{
    protected $table = 'galeri';
    protected $primaryKey = 'id_galeri';

    public function getAllPhotos()
    {
        $sql = "SELECT g.*, a.nama_lengkap as uploader 
                FROM {$this->table} g
                JOIN anggota a ON g.id_uploader = a.id_anggota
                ORDER BY g.tanggal_upload DESC";
        return $this->db->query($sql);
    }

    public function getPaginatedPhotos($limit, $offset)
    {
        $sql = "SELECT g.*, a.nama_lengkap as uploader 
                FROM {$this->table} g
                JOIN anggota a ON g.id_uploader = a.id_anggota
                ORDER BY g.tanggal_upload DESC
                LIMIT :limit OFFSET :offset";
        return $this->db->query($sql, ['limit' => $limit, 'offset' => $offset]);
    }

    public function countAllPhotos()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->query($sql);
        return $result[0]['total'] ?? 0;
    }

    public function getPaginatedApprovedPhotos($limit, $offset)
    {
        $sql = "SELECT g.*, a.nama_lengkap as uploader 
                FROM {$this->table} g
                JOIN anggota a ON g.id_uploader = a.id_anggota
                WHERE g.status = 'approved'
                ORDER BY g.tanggal_upload DESC
                LIMIT :limit OFFSET :offset";
        return $this->db->query($sql, ['limit' => $limit, 'offset' => $offset]);
    }

    public function countApprovedPhotos()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'approved'";
        $result = $this->db->query($sql);
        return $result[0]['total'] ?? 0;
    }

    public function getApprovedPhotos()
    {
        $sql = "SELECT g.*, a.nama_lengkap as uploader 
                FROM {$this->table} g
                JOIN anggota a ON g.id_uploader = a.id_anggota
                WHERE g.status = 'approved'
                ORDER BY g.tanggal_upload DESC";
        return $this->db->query($sql);
    }

    public function getPhotoById($id)
    {
        $result = $this->db->query("SELECT * FROM {$this->table} WHERE id_galeri = :id", ['id' => $id]);
        return $result[0] ?? null;
    }

    public function createPhoto($data)
    {
        $sql = "INSERT INTO {$this->table} (deskripsi, file_path, id_uploader, status) 
                VALUES (:deskripsi, :file_path, :id_uploader, :status)";

        return $this->db->execute($sql, [
            'deskripsi' => $data['deskripsi'],
            'file_path' => $data['file_path'],
            'id_uploader' => $data['id_uploader'],
            'status' => $data['status'] ?? 'pending'
        ]);
    }

    public function updatePhoto($id, $data)
    {
        $fields = [];
        $params = ['id' => $id];

        if (isset($data['deskripsi'])) {
            $fields[] = "deskripsi = :deskripsi";
            $params['deskripsi'] = $data['deskripsi'];
        }
        if (isset($data['status'])) {
            $fields[] = "status = :status";
            $params['status'] = $data['status'];
        }
        if (isset($data['id_admin_penilai'])) {
            $fields[] = "id_admin_penilai = :id_admin_penilai";
            $params['id_admin_penilai'] = $data['id_admin_penilai'];
        }
        if (isset($data['catatan_admin'])) {
            $fields[] = "catatan_admin = :catatan_admin";
            $params['catatan_admin'] = $data['catatan_admin'];
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id_galeri = :id";
        return $this->db->execute($sql, $params);
    }

    public function deletePhoto($id)
    {
        return $this->db->execute("DELETE FROM {$this->table} WHERE id_galeri = :id", ['id' => $id]);
    }
}
