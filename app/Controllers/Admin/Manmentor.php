<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
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

class Manmentor extends BaseController
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
        $this->usermentoraksesModel = new UserMentorAksesModel(); // Inisialisasi model User

    }
    public function index()
    {
        $dataUser = $this->userModel->getUserByLevel([3, 4, 5, 6])->getResultArray();
        $dataLevel = $this->user_levelModel->findByLevel([3, 4, 5, 6]);


        // Ambil mentor yang berlevel 2 dan 3 (semua kemungkinan mentor)
        $mentorLevel2 = $this->userModel->getUserByLevel([2])->getResultArray();
        $mentorLevel3 = $this->userModel->getUserByLevel([3])->getResultArray();

        // Susun opsi mentor untuk masing-masing user
        $mentorOptions = [];
        foreach ($dataUser as $user) {
            if ($user['level_user'] == 3) {
                $mentorOptions[$user['id']] = $mentorLevel2;
            } elseif (in_array($user['level_user'], [4, 5, 6])) {
                $mentorOptions[$user['id']] = $mentorLevel3;
            } else {
                $mentorOptions[$user['id']] = [];
            }
        }

        // Ambil data mentor yang sudah diset
        $aksesData = $this->usermentoraksesModel->findAll();
        $userMentorMapping = [];
        foreach ($aksesData as $a) {
            $userMentorMapping[$a['user_id']] = $a['mentor_id'];
        }

        // Kirim data ke view
        $data = [
            'level_akses' => $this->session->get('level'),
            'dtmenu' => $this->tampil_menu($this->session->get('level')),
            'nama_menu' => 'Kelola Mentor',
            'dataUser' => $dataUser,
            'dataLevel' => $dataLevel,
            'mentorOptions' => $mentorOptions,
            'userMentorMapping' => $userMentorMapping,
        ];

        return view('admin/kelolamentor', $data);
    }

    public function setMentor()
    {
        $user_id = $this->request->getPost('user_id');
        $mentor_id = $this->request->getPost('mentor_id');

        $existing = $this->usermentoraksesModel->where('user_id', $user_id)->first();
        if ($existing) {
            $this->usermentoraksesModel->update($existing['id'], [
                'mentor_id' => $mentor_id,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->usermentoraksesModel->insert([
                'user_id' => $user_id,
                'mentor_id' => $mentor_id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->back()->with('message', 'Mentor berhasil diperbarui');
    }

    public function kelolaform($levelId = null)
    {
        $levelId = (int) $levelId;
        $levelAkses = $this->user_levelModel->find($levelId);
        $namaLevel = $levelAkses->nama_level;


        // Siapkan data kompetensi sesuai level
        switch ($levelId) {
            case 3:
                $dataKompetensi = $this->pk3Model->orderBy('no ASC')->findAll();
                break;
            case 4:
                $dataKompetensi = $this->pk2Model->orderBy('no ASC')->findAll();
                break;
            case 5:
                $dataKompetensi = $this->pk1Model->orderBy('no ASC')->findAll();
                break;
            case 6:
                $dataKompetensi = $this->prapkModel->orderBy('no ASC')->findAll();
                break;
            default:
                return redirect()->to('/admin/manmentor');
        }

        // Kirim data ke view
        $data = [
            'level_akses' => $this->session->get('level'),
            'dtmenu' => $this->tampil_menu($this->session->get('level')),
            'nama_menu' => 'Mentoring',
            'dataKompetensi' => $dataKompetensi,
            'levelId' => $levelId,
            'namaLevel' => $namaLevel
        ];

        return view('admin/kelolaform', $data);
    }

    public function tambah_kompetensi()
    {
        $kategori = $this->request->getVar('kategori');
        $kompetensi = $this->request->getVar('kompetensi');

        if ($kategori && $kompetensi) {
            // Ambil nilai 'no' terbesar dari tabel
            $lastData = $this->prapkModel->orderBy('no', 'DESC')->first();
            $lastNo = $lastData ? (int) $lastData['no'] : 0;
            $newNo = $lastNo + 1;

            // Simpan data kompetensi baru
            $this->prapkModel->insert([
                'kategori' => $kategori,
                'kompetensi' => $kompetensi,
                'no' => $newNo
            ]);

            return redirect()->back()->with('message', 'Kompetensi berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Kategori dan kompetensi tidak boleh kosong');
    }
}


