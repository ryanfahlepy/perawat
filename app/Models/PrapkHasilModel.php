<?php

namespace App\Models;

use CodeIgniter\Model;

class PrapkHasilModel extends Model
{
    protected $table = 'tabel_prapk_hasil';
    protected $allowedFields = ['user_id', 'prapk_id', 'nilai_id', 'tanggal_mulai', 'tanggal_berakhir', 'tanggal_terakhir_penilaian', 'kompetensi_snapshot'];




    public function simpanData($userId, $prapkId, $nilaiId)
    {
        $existing = $this->where(['user_id' => $userId, 'prapk_id' => $prapkId])->first();

        if ($existing) {
            $this->update($existing['id'], [
                'nilai_id' => $nilaiId,
                'tanggal_terakhir_penilaian' => date('Y-m-d H:i:s')
            ]);

            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                log_message('error', 'Update gagal: ' . print_r($this->errors(), true));
                return false;
            }
        } else {
            $this->insert([
                'user_id' => $userId,
                'prapk_id' => $prapkId,
                'nilai_id' => $nilaiId,
                'tanggal_mulai' => date('Y-m-d'),
                'tanggal_terakhir_penilaian' => date('Y-m-d H:i:s'),
                'kompetensi_snapshot' => ''
            ]);

            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                log_message('error', 'Insert gagal: ' . print_r($this->errors(), true));
                return false;
            }
        }

    }
    public function getHasilByUserAndPrapk($userId, $prapkId)
    {
        return $this->where(['user_id' => $userId, 'prapk_id' => $prapkId])->first();
    }

    public function getAllHasilByUser($userId)
    {
        $rows = $this->where('user_id', $userId)->findAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['prapk_id']] = $row;
        }
        return $result;
    }





}


