<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\User_levelModel;
use App\Models\UserMentorAksesModel;
use App\Models\UserModel;
use App\Models\PrapkModel;
use App\Models\PrapkHasilModel;

class Manmentor extends BaseController
{
    public function __construct()
    {
        $this->user_levelModel = new User_levelModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->prapkModel = new PrapkModel(); // simpan ke properti kelas
        $this->prapkHasilModel = new PrapkHasilModel(); // simpan ke properti kelas
        $this->userModel = new UserModel(); // Inisialisasi model User
        $this->usermentoraksesModel = new UserMentorAksesModel(); // Inisialisasi model User
    }
    public function index()
    {
        $dataUser = $this->userModel->getUserByLevel([3, 4, 5, 6])->getResultArray();


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
            'nama_menu' => 'Master Mentor',
            'dataUser' => $dataUser,
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

}
