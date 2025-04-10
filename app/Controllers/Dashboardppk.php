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
        $pengadaanMulaiPerBulan = $this->pengadaanModel->jumlahPengadaanMulaiPerBulan($tahun_dipilih);

        // Format data untuk chart
        $bulan_mulai = [];
        $jumlah_mulai = [];

        foreach ($pengadaanMulaiPerBulan as $row) {
            $bulan_mulai[] = date("F", mktime(0, 0, 0, $row['bulan'], 1)); // ubah angka jadi nama bulan
            $jumlah_mulai[] = $row['jumlah'];
        }
        // Gabungkan data bulan dan jumlah menjadi array of objects
        $jumlah_pengadaan = [];
        foreach ($pengadaanMulaiPerBulan as $row) {
            $jumlah_pengadaan[] = [
                'bulan_mulai' => $row['bulan'],
                'jumlah_mulai' => $row['jumlah']
            ];
        }

        // Ambil data jumlah pengadaan berakhir per bulan
        $pengadaanBerakhirPerBulan = $this->pengadaanModel->jumlahPengadaanBerakhirPerBulan($tahun_dipilih);

        $bulan_berakhir = [];
        $jumlah_berakhir = [];

        foreach ($pengadaanBerakhirPerBulan as $row) {
            $bulan_berakhir[] = date("F", mktime(0, 0, 0, $row['bulan'], 1));
            $jumlah_berakhir[] = $row['jumlah'];
        }
        $distribusi_jenis = $this->pengadaanModel->distribusiPengadaanPerJenis($tahun_dipilih);

        // Ubah ke format untuk Chart.js
        $label_jenis = [];
        $data_jenis = [];

        foreach ($distribusi_jenis as $row) {
            $label_jenis[] = $row['jenis'];
            $data_jenis[] = (int) $row['jumlah'];
        }

        $distribusi_metode = $this->pengadaanModel->distribusiPengadaanPerMetode($tahun_dipilih);

        // Ubah ke format chart
        $label_metode = [];
        $data_metode = [];

        foreach ($distribusi_metode as $row) {
            $label_metode[] = $row['metode'];
            $data_metode[] = (int) $row['jumlah'];
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
            'perencanaan_belanja_dipa_disinfolahtal' => $this->pengadaanModel->perencanaan_belanja_dipa_disinfolahtal($tahun_dipilih),
            'pelaksanaan_belanja_dipa_disinfolahtal' => $this->pengadaanModel->pelaksanaan_belanja_dipa_disinfolahtal($tahun_dipilih),
            'pembayaran_belanja_dipa_disinfolahtal' => $this->pengadaanModel->pembayaran_belanja_dipa_disinfolahtal($tahun_dipilih),

            // Belanja Modal
            'jumlah_pengadaan_belanja_modal' => $this->pengadaanModel->jumlah_pengadaan_belanja_modal($tahun_dipilih),
            'perencanaan_belanja_dipa_mabesal' => $this->pengadaanModel->perencanaan_belanja_dipa_mabesal($tahun_dipilih),
            'pelaksanaan_belanja_dipa_mabesal' => $this->pengadaanModel->pelaksanaan_belanja_dipa_mabesal($tahun_dipilih),
            'pembayaran_belanja_dipa_mabesal' => $this->pengadaanModel->pembayaran_belanja_dipa_mabesal($tahun_dipilih),

            // Data grafik
            'bulan_mulai' => json_encode($bulan_mulai),
            'bulan_berakhir' => json_encode($bulan_berakhir),
            'jumlah_mulai' => json_encode($jumlah_mulai),
            'jumlah_berakhir' => json_encode($jumlah_berakhir),
            'label_jenis' => json_encode($label_jenis),
            'data_jenis' => json_encode($data_jenis),
            'label_metode' => json_encode($label_metode),
            'data_metode' => json_encode($data_metode),



        ];

        return view('layout/dashboardppk', $data);
    }

}
