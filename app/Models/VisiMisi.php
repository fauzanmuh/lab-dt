<?php

namespace App\Models;

use Core\Model;

class VisiMisi extends Model
{
    protected $table = 'profil_lab';
    protected $primaryKey = 'id';

    public function getAllVisiMisi()
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE jenis_konten IN ('visi', 'misi') ORDER BY jenis_konten DESC");
    }

    public function getByType($type)
    {
        $result = $this->db->query("SELECT * FROM {$this->table} WHERE jenis_konten = :type", ['type' => $type]);
        return $result[0] ?? null;
    }

    public function getVisi()
    {
        return $this->getByType('visi');
    }

    public function getMisi()
    {
        return $this->getByType('misi');
    }

    public function createVisiMisi($data)
    {
        $sql = "INSERT INTO {$this->table} (jenis_konten, isi_konten, updated_at) 
                VALUES (:jenis_konten, :isi_konten, CURRENT_TIMESTAMP)";

        return $this->db->execute($sql, [
            'jenis_konten' => $data['jenis_konten'],
            'isi_konten' => $data['isi_konten']
        ]);
    }

    public function updateVisiMisi($id, $data)
    {
        $fields = [];
        $params = ['id' => $id];

        if (isset($data['isi_konten'])) {
            $fields[] = "isi_konten = :isi_konten";
            $params['isi_konten'] = $data['isi_konten'];
        }

        $fields[] = "updated_at = CURRENT_TIMESTAMP";

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        return $this->db->execute($sql, $params);
    }

    public function deleteVisiMisi($id)
    {
        return $this->db->execute("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function upsertVisiMisi($type, $content)
    {
        $existing = $this->getByType($type);

        if ($existing) {
            return $this->updateVisiMisi($existing['id'], ['isi_konten' => $content]);
        } else {
            return $this->createVisiMisi([
                'jenis_konten' => $type,
                'isi_konten' => $content
            ]);
        }
    }
}
