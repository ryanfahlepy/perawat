<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PengadaanModel;

class Dashboardpp extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        
        $this->pengadaanModel = new PengadaanModel(); 
    }
    public function index()
    {
        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Dashboard Pejabat Pengadaan',
            'jumlah_pengadaan' => $this->pengadaanModel->jumlah_pengadaan(),
            'total_perencanaan' => $this->pengadaanModel->total_perencanaan(),
            'total_pelaksanaan' => $this->pengadaanModel->total_pelaksanaan(),
            'total_pembayaran' => $this->pengadaanModel->total_pembayaran(),
            'jumlah_pengadaan' => $this->pengadaanModel->jumlah_pengadaan()
        ];
        return view('pokja/dashboardpp', $data);
    }
}
