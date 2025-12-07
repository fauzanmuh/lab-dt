<?php

namespace App\Models;

use Core\Model;

class News extends Model
{
    protected $table = 'berita';
    protected $primaryKey = 'id_berita';

    public function getAllNews()
    {
        $sql = "SELECT b.*, a.nama_lengkap as penulis, a.foto_profil, a.username 
                FROM {$this->table} b
                JOIN anggota a ON b.id_penulis = a.id_anggota
                ORDER BY b.tanggal_posting DESC";
        return $this->db->query($sql);
    }

    public function getPaginatedNews($limit, $offset)
    {
        $sql = "SELECT b.*, a.nama_lengkap as penulis, a.foto_profil, a.username 
                FROM {$this->table} b
                JOIN anggota a ON b.id_penulis = a.id_anggota
                ORDER BY b.tanggal_posting DESC
                LIMIT :limit OFFSET :offset";
        return $this->db->query($sql, ['limit' => $limit, 'offset' => $offset]);
    }

    public function countAllNews()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->query($sql);
        return $result[0]['total'] ?? 0;
    }

    public function getApprovedNews()
    {
        $sql = "SELECT b.*, a.nama_lengkap as penulis, a.foto_profil, a.username 
                FROM {$this->table} b
                JOIN anggota a ON b.id_penulis = a.id_anggota
                WHERE b.status = 'approved'
                ORDER BY b.tanggal_posting DESC";
        return $this->db->query($sql);
    }

    public function getApprovedNewsByAuthor($authorId)
    {
        $sql = "SELECT b.*, a.nama_lengkap as penulis, a.foto_profil, a.username 
                FROM {$this->table} b
                JOIN anggota a ON b.id_penulis = a.id_anggota
                WHERE b.status = 'approved' AND b.id_penulis = :id
                ORDER BY b.tanggal_posting DESC";
        return $this->db->query($sql, ['id' => $authorId]);
    }

    public function getNewsById($id)
    {
        $result = $this->db->query("SELECT * FROM {$this->table} WHERE id_berita = :id", ['id' => $id]);
        return $result[0] ?? null;
    }

    public function createNews($data)
    {
        $sql = "INSERT INTO {$this->table} (judul, slug, isi_berita, gambar_utama, id_penulis, status) 
                VALUES (:judul, :slug, :isi_berita, :gambar_utama, :id_penulis, :status)";

        return $this->db->execute($sql, [
            'judul' => $data['judul'],
            'slug' => $this->generateSlug($data['judul']),
            'isi_berita' => $data['isi_berita'],
            'gambar_utama' => $data['gambar_utama'] ?? null,
            'id_penulis' => $data['id_penulis'],
            'status' => $data['status'] ?? 'pending'
        ]);
    }

    public function updateNews($id, $data)
    {
        $fields = [];
        $params = ['id' => $id];

        if (isset($data['judul'])) {
            $fields[] = "judul = :judul";
            $params['judul'] = $data['judul'];

            // Update slug if title changes
            $fields[] = "slug = :slug";
            $params['slug'] = $this->generateSlug($data['judul']);
        }
        if (isset($data['isi_berita'])) {
            $fields[] = "isi_berita = :isi_berita";
            $params['isi_berita'] = $data['isi_berita'];
        }
        if (isset($data['gambar_utama'])) {
            $fields[] = "gambar_utama = :gambar_utama";
            $params['gambar_utama'] = $data['gambar_utama'];
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

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id_berita = :id";
        return $this->db->execute($sql, $params);
    }

    public function deleteNews($id)
    {
        return $this->db->execute("DELETE FROM {$this->table} WHERE id_berita = :id", ['id' => $id]);
    }

    private function generateSlug($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return $text ?: 'n-a';
    }
}
