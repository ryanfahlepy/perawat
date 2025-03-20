<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\PengadaanModel;

class Dashboardppk extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();; 
        $this->pengadaanModel = new PengadaanModel(); 
    }
    public function index()
    {
        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Dashboard PPK',

            //Keseluruhan
            'jumlah_pengadaan' => $this->pengadaanModel->jumlah_pengadaan(),
            'total_perencanaan' => $this->pengadaanModel->total_perencanaan(),
            'total_pelaksanaan' => $this->pengadaanModel->total_pelaksanaan(),
            'total_pembayaran' => $this->pengadaanModel->total_pembayaran(),


            //Belanja Rutin
            'jumlah_pengadaan_belanja_rutin'=>$this->pengadaanModel->jumlah_pengadaan_belanja_rutin(),
            'perencanaan_belanja_rutin' => $this->pengadaanModel->perencanaan_belanja_rutin(),
            'pelaksanaan_belanja_rutin' => $this->pengadaanModel->pelaksanaan_belanja_rutin(),
            'pembayaran_belanja_rutin' => $this->pengadaanModel->pembayaran_belanja_rutin(),

            //Belanja Modal
            'jumlah_pengadaan_belanja_modal'=>$this->pengadaanModel->jumlah_pengadaan_belanja_modal(),
            'perencanaan_belanja_modal' => $this->pengadaanModel->perencanaan_belanja_modal(),
            'pelaksanaan_belanja_modal' => $this->pengadaanModel->pelaksanaan_belanja_modal(),
            'pembayaran_belanja_modal' => $this->pengadaanModel->pembayaran_belanja_modal(),
            
            'jumlah_pengadaan' => $this->pengadaanModel->jumlah_pengadaan() 
        ];
        return view('ppk/dashboardppk', $data);
    }
}
