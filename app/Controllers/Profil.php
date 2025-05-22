<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profil extends BaseController
{
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
    }
    public function index()
    {
        $userModel = new UserModel();
        $userId = session()->get('user_id');
        $user = $userModel->getUserById($userId);

        $data = [
            'level_akses' => session()->get('nama_level'),
            'dtmenu' => $this->tampil_menu(session()->get('level')),
            'nama_menu' => 'Profil',
            'user' => $user // ← kirim data user ke view
        ];

        return view('user/profil', $data);
    }
    public function update()
    {
        $userModel = new \App\Models\UserModel();
        $userId = session()->get('user_id');

        $data = [
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
            'tahun_lulus' => $this->request->getPost('tahun_lulus'),
            'status_kawin' => $this->request->getPost('status_kawin'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp'),
            'username' => $this->request->getPost('username'),
        ];

        // Validasi sederhana
        if (
            !$this->validate([
                'email' => 'required|valid_email',
                'no_hp' => 'required',
            ])
        ) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal');
        }

        $userModel->update($userId, $data);

        // Update juga nama di session jika diubah
        session()->set('nama', $data['nama']);

        return redirect()->to('/profil')->with('pesan', 'Data berhasil diperbarui');
    }

    public function update_foto()
    {
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('assets/dist/img/user/', $newName);

            // Simpan ke session atau database
            $userModel = new \App\Models\UserModel();
            $userModel->update(session()->get('id'), ['photo' => $newName]);

            session()->set('photo', $newName); // update sesi foto juga
            session()->setFlashdata('pesan', 'Foto profil berhasil diubah');
        }

        return redirect()->back();
    }

}
