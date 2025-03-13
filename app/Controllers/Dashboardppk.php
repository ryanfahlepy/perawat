<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PaketModel;
use App\Models\PengadaanModel;

class Dashboardppk extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->paketModel = new PaketModel(); 
        $this->pengadaanModel = new PengadaanModel(); 
    }
    public function index()
    {
        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Dashboard PPK',

            //Keseluruhan
            'jumlah_paket' => $this->paketModel->jumlah_paket(),
            'total_perencanaan' => $this->paketModel->total_perencanaan(),
            'total_pelaksanaan' => $this->paketModel->total_pelaksanaan(),
            'total_pembayaran' => $this->paketModel->total_pembayaran(),


            //Belanja Rutin
            'jumlah_paket_belanja_rutin'=>$this->paketModel->jumlah_paket_belanja_rutin(),
            'perencanaan_belanja_rutin' => $this->paketModel->perencanaan_belanja_rutin(),
            'pelaksanaan_belanja_rutin' => $this->paketModel->pelaksanaan_belanja_rutin(),
            'pembayaran_belanja_rutin' => $this->paketModel->pembayaran_belanja_rutin(),

            //Belanja Modal
            'jumlah_paket_belanja_modal'=>$this->paketModel->jumlah_paket_belanja_modal(),
            'perencanaan_belanja_modal' => $this->paketModel->perencanaan_belanja_modal(),
            'pelaksanaan_belanja_modal' => $this->paketModel->pelaksanaan_belanja_modal(),
            'pembayaran_belanja_modal' => $this->paketModel->pembayaran_belanja_modal(),
            
            'jumlah_pengadaan' => $this->pengadaanModel->jumlah_pengadaan() 
        ];
        return view('ppk/dashboardppk', $data);
    }
}
