<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KinerjaModel;
use App\Models\User_levelModel;
use App\Models\UserMentorAksesModel;
use App\Models\UserModel;
use App\Models\PrapkModel;
use App\Models\Pk1Model;
use App\Models\Pk2Model;
use App\Models\Pk3Model;
use App\Models\PrapkHasilModel;
use App\Models\Pk1HasilModel;
use App\Models\Pk2HasilModel;
use App\Models\Pk3HasilModel;

class Mankinerja extends BaseController
{
    public function __construct()
    {
        $this->user_levelModel = new User_levelModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
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
        $this->kinerjaModel = new KinerjaModel(); // Inisialisasi model User

    }
    public function index()
    {
        $dataKinerja = $this->kinerjaModel
            ->select('tabel_kinerja.*, tabel_user_level.nama_level')
            ->join('tabel_user_level', 'tabel_user_level.id = tabel_kinerja.level_user', 'left')
            ->asArray()
            ->findAll();

        $data = [
            'level_akses' => $this->session->get('level'),
            'dtmenu' => $this->tampil_menu($this->session->get('level')),
            'nama_menu' => 'Kelola Kinerja',
            'data_kinerja' => $dataKinerja,
            'user_levels' => $this->user_levelModel->findAll(),
        ];

        return view('admin/kelolakinerja', $data);
    }

    public function ajax_update_level()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $level_id = $this->request->getPost('level_id');
            $checked = $this->request->getPost('checked') == '1';

            $item = $this->kinerjaModel->find($id);
            if (!$item) {
                return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
            }

            $existingLevels = explode(',', $item['level_user'] ?? '');

            if ($checked) {
                if (!in_array($level_id, $existingLevels)) {
                    $existingLevels[] = $level_id;
                }
            } else {
                $existingLevels = array_filter($existingLevels, fn($v) => $v != $level_id);
            }

            $existingLevels = array_unique($existingLevels);
            $level_user = implode(',', $existingLevels);

            $this->kinerjaModel->update($id, ['level_user' => $level_user]);

            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Bukan request AJAX']);
    }


    public function ajax_update_data_kinerja()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $field = $this->request->getPost('field');
            $value = $this->request->getPost('value');

            $allowedFields = ['indikator', 'kode_kpi', 'formula', 'sumber_data', 'periode_assesment', 'bobot', 'target', 'deskripsi_target'];

            if (!in_array($field, $allowedFields)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Field tidak diperbolehkan']);
            }

            $this->kinerjaModel->update($id, [$field => $value]);

            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Bukan permintaan AJAX']);
    }

    public function create()
    {
        if ($this->request->isAJAX()) {
            // Bersihkan bobot dari simbol %
            $bobotInput = $this->request->getPost('bobot');
            $bobotClean = str_replace('%', '', $bobotInput);
            $bobot = floatval(trim($bobotClean));

            $target = $this->request->getPost('target');

            $data = [
                'indikator'         => $this->request->getPost('indikator'),
                'kode_kpi'          => $this->request->getPost('kode_kpi'),
                'formula'           => $this->request->getPost('formula'),
                'sumber_data'       => $this->request->getPost('sumber_data'),
                'periode_assesment' => $this->request->getPost('periode_assesment'),
                'bobot'             => $bobot,
                'target'            => floatval($target), // nilai bebas, bisa desimal
                'deskripsi_target'  => $this->request->getPost('deskripsi_target'),
                'level_user'        => ''
            ];

            // Validasi data
            $this->validation->setRules([
                'indikator'         => 'required',
                'kode_kpi'          => 'required',
                'formula'           => 'required',
                'sumber_data'       => 'required',
                'periode_assesment' => 'required|in_list[Bulanan,Tahunan]',
                'bobot'             => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
                'target'            => 'required|numeric', // desimal OK, tidak dibatasi range
                'deskripsi_target'  => 'required',
            ]);

            if (!$this->validation->run($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->validation->getErrors()
                ]);
            }

            // Simpan ke database
            if ($this->kinerjaModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Data berhasil ditambahkan'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data'
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Bukan permintaan AJAX'
        ]);
    }



    public function delete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $model = new \App\Models\KinerjaModel();

        if ($model->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data']);
        }
    }





}

