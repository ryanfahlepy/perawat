<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NotifikasiModel;

class Notifikasi extends BaseController
{
    protected $notifikasiModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->notifikasiModel = new NotifikasiModel();
    }

    public function tandaiDibaca()
    {
        $notifId = $this->request->getPost('notif_id');

        // Memuat model notifikasi
        $notifikasiModel = new \App\Models\NotifikasiModel();

        // Menandai notifikasi sebagai "dibaca"
        $data = [
            'status' => 'dibaca'
        ];

        // Update status notifikasi
        $result = $notifikasiModel->update($notifId, $data);

        // Cek apakah update berhasil
        if ($result) {
            // Mengembalikan response sukses
            return $this->response->setJSON(['status' => 'success']);
        } else {
            // Mengembalikan response gagal
            return $this->response->setJSON(['status' => 'error']);
        }
    }

}



