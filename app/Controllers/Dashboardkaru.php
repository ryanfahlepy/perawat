<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PrapkModel;
use App\Models\Pk1Model;
use App\Models\Pk2Model;
use App\Models\Pk3Model;
use App\Models\PrapkHasilModel;
use App\Models\Pk1HasilModel;
use App\Models\Pk2HasilModel;
use App\Models\Pk3HasilModel;
use App\Models\UserMentorAksesModel;
use App\Models\DaftarFormModel;


class Dashboardkaru extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->prapkModel = new PrapkModel(); // simpan ke properti kelas
        $this->pk1Model = new Pk1Model(); // simpan ke properti kelas
        $this->pk2Model = new Pk2Model(); // simpan ke properti kelas
        $this->pk3Model = new Pk3Model(); // simpan ke properti kelas
        $this->prapkHasilModel = new PrapkHasilModel(); // simpan ke properti kelas
        $this->pk1HasilModel = new Pk1HasilModel(); // simpan ke properti kelas
        $this->pk2HasilModel = new Pk2HasilModel(); // simpan ke properti kelas
        $this->pk3HasilModel = new Pk3HasilModel(); // simpan ke properti kelas
        $this->userModel = new UserModel(); // Inisialisasi model User
        $this->userMentorAksesModel = new UserMentorAksesModel(); // Inisialisasi model User
        $this->daftarFormModel = new DaftarFormModel(); // tambahkan ini
    }
    public function index()
    {
        $users = $this->userModel->getAllUsersWithLevel();

        // Hitung jumlah per nama_level secara dinamis
        $countByLevel = [];
        $daftarFormModel = new \App\Models\DaftarFormModel();

        // Gunakan query builder dari model
        // Query aktivitas mentoring per bulan
        $query = $daftarFormModel->select("DATE_FORMAT(tanggal_mulai, '%Y-%m') as bulan, COUNT(*) as total")
            ->groupBy("bulan")
            ->orderBy("bulan", "ASC")
            ->findAll();

        $mentoringLabels = [];
        $mentoringCounts = [];

        foreach ($query as $row) {
            // Format label menjadi Nama Bulan (contoh: '2025-05' => 'Mei 2025')
            $timestamp = strtotime($row['bulan'] . "-01");
            $mentoringLabels[] = date('F Y', $timestamp); // atau date('M Y') untuk singkat
            $mentoringCounts[] = $row['total'];
        }


        foreach ($users as $user) {
            $nama_level = $user->nama_level;
            if (!isset($countByLevel[$nama_level])) {
                $countByLevel[$nama_level] = 0;
            }
            $countByLevel[$nama_level]++;
        }

        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Dashboard Karu',
            'users' => $users,
            'countByLevel' => $countByLevel,
            'mentoringLabels' => $mentoringLabels,
            'mentoringCounts' => $mentoringCounts
            // Kirim ke view
        ];

        return view('layout/dashboardkaru', $data);
    }


    public function resume_mentoring($userId = null)
    {
        $session = session();
        $loggedInUserId = $session->get('user_id');
        $level = $session->get('level');

        if ($userId === null) {
            return redirect()->to('/dashboardkaru')->with('error', 'User ID tidak valid');
        }

        // Ambil data user target
        $userData = $this->userModel->find($userId);
        if (!$userData) {
            return redirect()->to('/dashboardkaru')->with('error', 'User tidak ditemukan');
        }

        // Ambil level user target, karena data pk dan hasil tergantung level mentee
        $levelUser = (int) $userData->level_user ?? null;

        // Ambil hasil mentoring user target dan mentor logged-in
        $mentorId = $loggedInUserId; // mentor adalah user yang login saat ini

        $dataPk = [];
        $dataHasilRaw = [];

        if ($mentorId) {
            switch ($levelUser) {
                case 3:
                    $dataPk = $this->pk3Model->findAll();
                    $dataHasilRaw = $this->pk3HasilModel->getAllHasilByUserAndMentor($userId, $mentorId);
                    break;
                case 4:
                    $dataPk = $this->pk2Model->findAll();
                    $dataHasilRaw = $this->pk2HasilModel->getAllHasilByUserAndMentor($userId, $mentorId);
                    break;
                case 5:
                    $dataPk = $this->pk1Model->findAll();
                    $dataHasilRaw = $this->pk1HasilModel->getAllHasilByUserAndMentor($userId, $mentorId);
                    break;
                case 6:
                    $dataPk = $this->prapkModel->findAll();
                    $dataHasilRaw = $this->prapkHasilModel->getAllHasilByUserAndMentor($userId, $mentorId);
                    break;
                default:
                    $dataPk = [];
                    $dataHasilRaw = [];
                    break;
            }
        }

        // Susun hasil indexed by kompetensi_id agar mudah akses di view
        $dataHasil = [];
        foreach ($dataHasilRaw as $hasil) {
            $dataHasil[$hasil['kompetensi_id']] = $hasil;
        }

        // Ambil daftar form untuk user target tanpa filter berdasarkan mentor_id
        $daftarForms = $this->daftarFormModel->where('user_id', $userId)
            ->orderBy('tanggal_mulai', 'DESC')  // Hanya berdasarkan user_id
            ->findAll();

        // Format tanggal dan hitung progress tiap form
        $listKompetensiId = [];
        foreach ($dataPk as $pk) {
            $listKompetensiId[] = (int) $pk['id'];
        }
        $totalKompetensi = count($listKompetensiId);

        foreach ($daftarForms as &$form) {
            $formId = (int) $form['id'];
            $form['tanggal_mulai_formatted'] = date('d-m-Y H:i', strtotime($form['tanggal_mulai']));
            $form['tanggal_berakhir_formatted'] = $form['tanggal_berakhir'] ? date('d-m-Y H:i', strtotime($form['tanggal_berakhir'])) : '-';

            $kompetensiTerpenuhi = [];

            foreach ($dataHasilRaw as $hasil) {
                if ((int) $hasil['form_id'] === $formId) {  // **tidak filter nilai_id, terima semua nilai_id**
                    $kompetensiId = (int) $hasil['kompetensi_id'];
                    if (in_array($kompetensiId, $listKompetensiId)) {
                        $kompetensiTerpenuhi[$kompetensiId] = true; // tandai sudah diisi nilai apapun
                    }
                }
            }

            $jumlahTerpenuhi = count($kompetensiTerpenuhi);

            $form['progress'] = ($totalKompetensi > 0) ? round(($jumlahTerpenuhi / $totalKompetensi) * 100, 2) : 0;
        }
        unset($form);

        $data = [
            'level_akses' => $level,
            'dtmenu' => $this->tampil_menu($level),
            'nama_menu' => 'Daftar Form',
            'daftarForms' => $daftarForms,
            'dataPk' => $dataPk,
            'dataHasil' => $dataHasil,
            'userData' => $userData,
            'session' => $session,
            'mentorId' => $mentorId,
        ];

        return view('layout/resume_mentoring', $data);
    }



}
