<?php

namespace App\Models;

use Core\Model;

class Contact extends Model
{
    protected $table = 'info_lab';
    protected $primaryKey = 'id';

    public function getContactInfo()
    {
        $result = $this->db->query("SELECT * FROM {$this->table} LIMIT 1");
        return $result[0] ?? null;
    }

    public function updateContactInfo($data)
    {
        $info = $this->getContactInfo();

        if ($info) {
            $fields = [];
            $params = ['id' => $info['id']];

            if (isset($data['nama_lab'])) {
                $fields[] = "nama_lab = :nama_lab";
                $params['nama_lab'] = $data['nama_lab'];
            }
            if (isset($data['alamat'])) {
                $fields[] = "alamat = :alamat";
                $params['alamat'] = $data['alamat'];
            }
            if (isset($data['email'])) {
                $fields[] = "email = :email";
                $params['email'] = $data['email'];
            }
            if (isset($data['telepon'])) {
                $fields[] = "telepon = :telepon";
                $params['telepon'] = $data['telepon'];
            }
            if (isset($data['link_maps'])) {
                $fields[] = "link_maps = :link_maps";
                $params['link_maps'] = $data['link_maps'];
            }
            if (isset($data['link_instagram'])) {
                $fields[] = "link_instagram = :link_instagram";
                $params['link_instagram'] = $data['link_instagram'];
            }
            if (isset($data['link_youtube'])) {
                $fields[] = "link_youtube = :link_youtube";
                $params['link_youtube'] = $data['link_youtube'];
            }
            if (isset($data['link_linkedin'])) {
                $fields[] = "link_linkedin = :link_linkedin";
                $params['link_linkedin'] = $data['link_linkedin'];
            }
            if (isset($data['link_facebook'])) {
                $fields[] = "link_facebook = :link_facebook";
                $params['link_facebook'] = $data['link_facebook'];
            }
            if (isset($data['link_twitter'])) {
                $fields[] = "link_twitter = :link_twitter";
                $params['link_twitter'] = $data['link_twitter'];
            }
            if (isset($data['deskripsi'])) {
                $fields[] = "deskripsi = :deskripsi";
                $params['deskripsi'] = $data['deskripsi'];
            }

            $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
            return $this->db->execute($sql, $params);
        } else {
            $sql = "INSERT INTO {$this->table} (nama_lab, alamat, email, telepon, link_maps, link_instagram, link_youtube, link_linkedin, link_facebook, link_twitter, deskripsi) 
                    VALUES (:nama_lab, :alamat, :email, :telepon, :link_maps, :link_instagram, :link_youtube, :link_linkedin, :link_facebook, :link_twitter, :deskripsi)";

            return $this->db->execute($sql, [
                'nama_lab' => $data['nama_lab'] ?? 'Lab DT',
                'alamat' => $data['alamat'] ?? '',
                'email' => $data['email'] ?? '',
                'telepon' => $data['telepon'] ?? '',
                'link_maps' => $data['link_maps'] ?? '',
                'link_instagram' => $data['link_instagram'] ?? '',
                'link_youtube' => $data['link_youtube'] ?? '',
                'link_linkedin' => $data['link_linkedin'] ?? '',
                'link_facebook' => $data['link_facebook'] ?? '',
                'link_twitter' => $data['link_twitter'] ?? '',
                'deskripsi' => $data['deskripsi'] ?? ''
            ]);
        }
    }
}
