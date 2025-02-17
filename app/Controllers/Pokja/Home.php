<?php

namespace App\Controllers\Pokja;

use App\Controllers\BaseController;
use App\Models\PengadaanModel;

class Home extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->pengadaanModel = new PengadaanModel();  // Menginisialisasi model
    }

    public function index()
    {
        // Mengambil semua data pengadaan
        $pengadaanData = $this->pengadaanModel->getAllPengadaan();

        $data = [
            'level_akses' => session()->get('nama_level'),
            'dtmenu' => $this->tampil_menu(session()->get('level')),
            'nama_menu' => 'Daftar Pengadaan',
            'pengadaanData' => $pengadaanData // Menyisipkan data pengadaan ke view
        ];

        return view('pokja/pengadaan', $data);
    }
}
