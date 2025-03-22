<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\PengadaanModel;

class Dashboardppk extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        ;
        $this->pengadaanModel = new PengadaanModel();
    }
    public function index()
    {
        // Ambil tahun dari parameter GET
        $tahun_dipilih = $this->request->getGet('tahun');

        // Ambil semua tahun yang tersedia dari tabel_pengadaan
        $tahun_tersedia = $this->pengadaanModel
            ->select('tahun_anggaran')
            ->distinct()
            ->orderBy('tahun_anggaran', 'DESC')
            ->findAll();

        // Ambil data jumlah pengadaan per bulan berdasarkan tahun
        $pengadaanPerBulan = $this->pengadaanModel->jumlahPengadaanPerBulan($tahun_dipilih);

        // Format data untuk chart
        $bulan = [];
        $jumlah = [];

        foreach ($pengadaanPerBulan as $row) {
            $bulan[] = date("F", mktime(0, 0, 0, $row['bulan'], 1)); // ubah angka jadi nama bulan
            $jumlah[] = $row['jumlah'];
        }
        // Gabungkan data bulan dan jumlah menjadi array of objects
        $jumlah_pengadaan = [];
        foreach ($pengadaanPerBulan as $row) {
            $jumlah_pengadaan[] = [
                'bulan' => $row['bulan'],
                'jumlah' => $row['jumlah']
            ];
        }
        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Dashboard PPK',

            // Tahun
            'tahun_dipilih' => $tahun_dipilih,
            'tahun_tersedia' => $tahun_tersedia,

            // Statistik keseluruhan (filtered by tahun)
            'jumlah_pengadaan' => $this->pengadaanModel->jumlah_pengadaan($tahun_dipilih),
            'total_perencanaan' => $this->pengadaanModel->total_perencanaan($tahun_dipilih),
            'total_pelaksanaan' => $this->pengadaanModel->total_pelaksanaan($tahun_dipilih),
            'total_pembayaran' => $this->pengadaanModel->total_pembayaran($tahun_dipilih),

            // Belanja Rutin
            'jumlah_pengadaan_belanja_rutin' => $this->pengadaanModel->jumlah_pengadaan_belanja_rutin($tahun_dipilih),
            'perencanaan_belanja_rutin' => $this->pengadaanModel->perencanaan_belanja_rutin($tahun_dipilih),
            'pelaksanaan_belanja_rutin' => $this->pengadaanModel->pelaksanaan_belanja_rutin($tahun_dipilih),
            'pembayaran_belanja_rutin' => $this->pengadaanModel->pembayaran_belanja_rutin($tahun_dipilih),

            // Belanja Modal
            'jumlah_pengadaan_belanja_modal' => $this->pengadaanModel->jumlah_pengadaan_belanja_modal($tahun_dipilih),
            'perencanaan_belanja_modal' => $this->pengadaanModel->perencanaan_belanja_modal($tahun_dipilih),
            'pelaksanaan_belanja_modal' => $this->pengadaanModel->pelaksanaan_belanja_modal($tahun_dipilih),
            'pembayaran_belanja_modal' => $this->pengadaanModel->pembayaran_belanja_modal($tahun_dipilih),

            // Data grafik
            'bulan' => json_encode($bulan),
            'jumlah' => json_encode($jumlah),
        ];

        return view('layout/dashboardppk', $data);
    }

}
