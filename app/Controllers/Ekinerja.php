<?php


namespace App\Controllers;
use App\Models\KinerjaModel;
use App\Models\HasilKinerjaModel;
use App\Models\User_levelModel;


class Ekinerja extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->kinerjaModel = new KinerjaModel();
        $this->user_levelModel = new User_levelModel();
        $this->hasilKinerjaModel = new HasilKinerjaModel();
    }

    public function index()
    {
        $dataKinerja = $this->kinerjaModel
            ->select('tabel_kinerja.*, tabel_user_level.nama_level, tabel_hasil_kinerja.hasil AS hasil_aktual')
            ->join('tabel_user_level', 'tabel_user_level.id = tabel_kinerja.level_user', 'left')
            ->join('tabel_hasil_kinerja', 'tabel_hasil_kinerja.kinerja_id = tabel_kinerja.id AND tabel_hasil_kinerja.user_id = ' . $this->session->get('user_id'), 'left')
            ->asArray()
            ->findAll();



        $data = [
            'level_akses' => $this->session->get('level'),
            'dtmenu' => $this->tampil_menu($this->session->get('level')),
            'nama_menu' => 'E-Kinerja',
            'data_kinerja' => $dataKinerja,
            'user_levels' => $this->user_levelModel->findAll(),
        ];



        return view('layout/ekinerja', $data);
    }
    public function ajax_update_field()
    {
        if ($this->request->isAJAX()) {
            $kinerja_id = $this->request->getPost('id'); // ini adalah ID dari tabel_kinerja
            $field = $this->request->getPost('field');
            $value = $this->request->getPost('value');
            $user_id = $this->session->get('user_id');

            // Validasi field
            $allowedFields = ['hasil'];
            if (!in_array($field, $allowedFields)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Field tidak diperbolehkan']);
            }

            // Cari apakah data hasil_kinerja sudah ada
            $existing = $this->hasilKinerjaModel
                ->where('user_id', $user_id)
                ->where('kinerja_id', $kinerja_id)
                ->first();

            $dataToSave = [
                'user_id' => $user_id,
                'kinerja_id' => $kinerja_id,
                'hasil' => $value
            ];

            if ($existing) {
                // update
                $this->hasilKinerjaModel->update($existing['id'], $dataToSave);
            } else {
                // insert
                $this->hasilKinerjaModel->insert($dataToSave);
            }

            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Bukan permintaan AJAX']);
    }

}