<?php

namespace App\Models;

use CodeIgniter\Model;

class Pk1HasilModel extends Model
{
    protected $table = 'tabel_pk1_hasil';
    protected $allowedFields = ['form_id', 'user_id', 'mentor_id', 'kompetensi_id', 'nilai_id', 'tanggal_mulai', 'tanggal_berakhir', 'tanggal_terakhir_penilaian', 'kompetensi_snapshot', 'catatan', 'remedial'];




    public function simpanData($userId, $kompetensiId, $nilaiId, $mentorId)
    {
        $existing = $this->where(['user_id' => $userId, 'kompetensi_id' => $kompetensiId])->first();

        // Menyiapkan data untuk insert/update
        $data = [
            'user_id' => $userId,
            'kompetensi_id' => $kompetensiId,
            'nilai_id' => $nilaiId,
            'mentor_id' => $mentorId,
            'tanggal_terakhir_penilaian' => date('Y-m-d H:i:s')
        ];

        if ($existing) {
            // Update data jika sudah ada
            $this->update($existing['id'], $data);

            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                log_message('error', 'Update gagal: ' . print_r($this->errors(), true));
                return false;
            }
        } else {
            // Insert data baru
            $data['tanggal_mulai'] = date('Y-m-d');
            $data['kompetensi_snapshot'] = ''; // Menambahkan kompetensi_snapshot jika perlu

            $this->insert($data);

            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                log_message('error', 'Insert gagal: ' . print_r($this->errors(), true));
                return false;
            }
        }
    }

    public function getHasilByUserAndPrapk($userId, $kompetensiId)
    {
        return $this->where(['user_id' => $userId, 'kompetensi_id' => $kompetensiId])->first();
    }

    public function getAllHasilByUserAndMentor($userId, $mentorId, $formId = null)
    {
        $this->where('user_id', $userId)
            ->where('mentor_id', $mentorId);

        if ($formId !== null) {
            $this->where('form_id', $formId);
        }

        $rows = $this->findAll();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['kompetensi_id']] = $row;
        }
        return $result;
    }





}


