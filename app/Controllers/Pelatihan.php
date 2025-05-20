<?php

namespace App\Controllers;

use App\Models\PelatihanWajibModel;
use App\Models\PelatihanHasilModel;
use App\Models\PelatihanTambahanModel;
use App\Models\UserModel;
use CodeIgniter\Files\File;

class Pelatihan extends BaseController
{
    protected $pelatihanWajibModel;
    protected $pelatihanHasilModel;
    protected $pelatihanTambahanModel;
    protected $session;
    protected $userModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->pelatihanWajibModel = new PelatihanWajibModel();
        $this->pelatihanHasilModel = new PelatihanHasilModel();
        $this->pelatihanTambahanModel = new PelatihanTambahanModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $session = session();
        $user_id = $session->get('user_id');
        $nama = $session->get('nama');
        $level = $session->get('level'); // tambahkan ini agar tidak perlu panggil lagi


        // Ambil level_user dari tabel user
        $user = $this->userModel->find($user_id);
        $level_user = $user->level_user ?? null;

        $nama_level = null;

        // Hanya jika bukan admin (level != 1), ambil nama_level
        if ($level != 1) {
            $userLevelModel = new \App\Models\User_levelModel();
            $user_level_data = $userLevelModel->where('id', $level_user)->first();
            $nama_level = $user_level_data->nama_level ?? null;
        }

        // Ambil daftar pelatihan wajib
        if ($level == 1) {
            // ADMIN: ambil semua pelatihan wajib
            $pelatihan_wajib = $this->pelatihanWajibModel->findAll();
        } else {
            // USER: ambil berdasarkan level user
            $pelatihan_wajib = $this->pelatihanWajibModel
                ->where('level', $nama_level)
                ->findAll();
        }

        // Ambil pelatihan hasil yang sudah diisi user
        $pelatihan_terisi = $this->pelatihanHasilModel
            ->where('user_id', $user_id)
            ->findAll();

        $pelatihan_map = [];
        foreach ($pelatihan_terisi as $item) {
            $pelatihan_map[$item['pelatihan_id']] = $item;
        }

        // Ambil pelatihan tambahan yang sudah diisi user
        $pelatihan_tambahan = $this->pelatihanTambahanModel
            ->where('user_id', $user_id)
            ->findAll();

        $users = $this->userModel->getAllUsersWithLevel();
        if ($level == 2) {
            return view('/layout/daftar_pelatihan_perawat', [
                'dtmenu' => $this->tampil_menu($level),
                'nama_menu' => 'Pelatihan',
                'user' => $user,
                'nama' => $nama,
                'users' => $users,
                'pelatihan_wajib' => $pelatihan_wajib,
                'pelatihan_map' => $pelatihan_map,
                'pelatihan_tambahan' => $pelatihan_tambahan,
            ]);
        }

        return view('/layout/pelatihan', [

            'dtmenu' => $this->tampil_menu($level),
            'nama_menu' => 'Pelatihan',
            'user' => $user,
            'nama' => $nama,
            'pelatihan_wajib' => $pelatihan_wajib,
            'pelatihan_map' => $pelatihan_map,
            'pelatihan_tambahan' => $pelatihan_tambahan,
        ]);
    }

    public function lihat_pelatihan($user_id)
    {
        $session = session();
        $nama = $session->get('nama');
        $level = $session->get('level'); // tambahkan ini agar tidak perlu panggil lagi


        // Ambil level_user dari tabel user
        $user = $this->userModel->find($user_id);
        $level_user = $user->level_user ?? null;

        $nama_level = null;

        // Hanya jika bukan admin (level != 1), ambil nama_level
        if ($level != 1) {
            $userLevelModel = new \App\Models\User_levelModel();
            $user_level_data = $userLevelModel->where('id', $level_user)->first();
            $nama_level = $user_level_data->nama_level ?? null;
        }

        // Ambil daftar pelatihan wajib
        if ($level == 1) {
            // ADMIN: ambil semua pelatihan wajib
            $pelatihan_wajib = $this->pelatihanWajibModel->findAll();
        } else {
            // USER: ambil berdasarkan level user
            $pelatihan_wajib = $this->pelatihanWajibModel
                ->where('level', $nama_level)
                ->findAll();
        }

        // Ambil pelatihan hasil yang sudah diisi user
        $pelatihan_terisi = $this->pelatihanHasilModel
            ->where('user_id', $user_id)
            ->findAll();

        $pelatihan_map = [];
        foreach ($pelatihan_terisi as $item) {
            $pelatihan_map[$item['pelatihan_id']] = $item;
        }

        // Ambil pelatihan tambahan yang sudah diisi user
        $pelatihan_tambahan = $this->pelatihanTambahanModel
            ->where('user_id', $user_id)
            ->findAll();

        return view('/layout/pelatihan', [

            'dtmenu' => $this->tampil_menu($level),
            'nama_menu' => 'Pelatihan',
            'user' => $user,
            'nama' => $nama,
            'pelatihan_wajib' => $pelatihan_wajib,
            'pelatihan_map' => $pelatihan_map,
            'pelatihan_tambahan' => $pelatihan_tambahan,
        ]);
    }

    public function detail($pelatihan_id)
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Ambil data pelatihan wajib
        $pelatihan = $this->pelatihanWajibModel->find($pelatihan_id);

        if (!$pelatihan) {
            return redirect()->to('/pelatihan')->with('error', 'Data pelatihan tidak ditemukan.');
        }

        $kategori = $pelatihan['kategori'];

        // Ambil daftar pelatihan yang sudah diikuti user berdasarkan pelatihan_id dan kategori
        $pelatihan_dijalani = $this->pelatihanHasilModel
            ->where('user_id', $user_id)
            ->where('pelatihan_id', $pelatihan_id)
            ->findAll();

        return view('/layout/detail_pelatihan', [
            'dtmenu' => $this->tampil_menu($this->session->get('level')),
            'nama_menu' => 'Detail',
            'pelatihan' => $pelatihan,
            'kategori' => $kategori,
            'pelatihan_list' => $pelatihan_dijalani,
        ]);
    }

    public function simpan()
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Ambil data dari form
        $judul = $this->request->getPost('judul');
        $lokasi = $this->request->getPost('lokasi');
        $tanggal = $this->request->getPost('tanggal');
        $berlaku = $this->request->getPost('berlaku');
        $kategori = $this->request->getPost('kategori');

        // Handle file upload
        $sertifikat = $this->request->getFile('sertifikat');

        $fileName = null;
        if ($sertifikat->isValid() && !$sertifikat->hasMoved()) {
            // Generate a random file name
            $fileName = $sertifikat->getRandomName();
            // Move the file to the uploads/sertifikat folder
            $sertifikat->move('uploads/sertifikat', $fileName);
        }

        // Prepare data to be saved
        $data = [
            'judul' => $judul,
            'lokasi' => $lokasi,
            'tanggal' => $tanggal,
            'berlaku' => $berlaku,
            'kategori' => $kategori,
            'sertifikat' => $fileName,
            'user_id' => $user_id,
            'pelatihan_id' => $this->pelatihanWajibModel->getPelatihanIdByKategori($kategori), // Assume you have a method to get pelatihan_id by category
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $pelatihan_id = $data['pelatihan_id'];
        // Save the data into PelatihanTambahanModel (or another model if necessary)
        $this->pelatihanHasilModel->insert($data);

        return redirect()->to('/pelatihan/detail/' . $pelatihan_id)
            ->with('success', 'Pelatihan berhasil ditambahkan!');
    }
    public function simpanPelatihanTambahan()
    {
        $session = session();
        $user_id = $session->get('user_id');

        $validation = \Config\Services::validation();

        // Validasi input
        $rules = [
            'judul' => 'required|min_length[3]|max_length[255]',
            'jenis' => 'required|min_length[3]|max_length[255]',
            'lokasi' => 'required|max_length[255]',
            'tanggal' => 'required|valid_date',
            'berlaku' => 'required|valid_date',
            'sertifikat' => 'uploaded[sertifikat]|max_size[sertifikat,2048]|ext_in[sertifikat,pdf,jpg,jpeg,png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $validation->getErrors()));
        }

        $fileSertifikat = $this->request->getFile('sertifikat');
        if ($fileSertifikat->isValid() && !$fileSertifikat->hasMoved()) {
            $newName = $fileSertifikat->getRandomName();
            $fileSertifikat->move(ROOTPATH . 'public/uploads/sertifikat', $newName);
        } else {
            $newName = null;
        }

        $data = [
            'user_id' => $user_id,
            'judul' => $this->request->getPost('judul'),
            'jenis' => $this->request->getPost('jenis'),
            'lokasi' => $this->request->getPost('lokasi'),
            'tanggal' => $this->request->getPost('tanggal'),
            'berlaku' => $this->request->getPost('berlaku'),
            'sertifikat' => $newName,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->pelatihanTambahanModel->insert($data);

        return redirect()->to('/pelatihan')->with('message', 'Pelatihan tambahan berhasil ditambahkan.');
    }

    public function simpanadmin()
    {
        $id = $this->request->getPost('id');
        $kategori = $this->request->getPost('kategori');
        $level = $this->request->getPost('level');

        $data = [
            'kategori' => $kategori,
            'level' => $level,
        ];

        if ($id) {
            // Update
            $this->pelatihanWajibModel->update($id, $data);
            $message = 'Kompetensi berhasil diperbarui.';
        } else {
            // Insert
            $this->pelatihanWajibModel->insert($data);
            $message = 'Kompetensi baru berhasil ditambahkan.';
        }

        return redirect()->to('/pelatihan')->with('success', $message);
    }

    public function delete($id)
    {
        $this->pelatihanWajibModel->delete($id);
        return redirect()->to('/pelatihan')->with('success', 'Kompetensi berhasil dihapus.');
    }



}
