<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
Use App\Models\InovasiModel;
Use App\Models\VoteModel;
Use App\Models\KomentarModel;
Use App\Models\PersetujuanInovasiModel;

class EInovasi extends BaseController
{

    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $statusFilter = $this->request->getGet('status'); // ambil dari query string
        $inovasiModel = new InovasiModel();
        $userModel = new UserModel();
        $voteModel = new VoteModel();
        $komentarModel = new KomentarModel();
        $persetujuanModel = new PersetujuanInovasiModel();

        $inovasiList = $statusFilter
            ? $inovasiModel->where('status', $statusFilter)->findAll()
            : $inovasiModel->findAll();

        $inovasiData = [];

        foreach ($inovasiList as $item) {
            $jumlahSetuju = $voteModel->where(['inovasi_id' => $item['id'], 'vote' => 'setuju'])->countAllResults();
            $jumlahTidakSetuju = $voteModel->where(['inovasi_id' => $item['id'], 'vote' => 'tidak_setuju'])->countAllResults();

            $komentar = $komentarModel
                ->select('tabel_komentar_inovasi.*, tabel_user.nama, tabel_user.photo')
                ->join('tabel_user', 'tabel_user.id = tabel_komentar_inovasi.user_id')
                ->where('inovasi_id', $item['id'])
                ->findAll();

            $pengusul = $userModel->select('tabel_user.id, tabel_user.nama, tabel_user.photo, tabel_user_level.nama_level AS grade')
                ->join('tabel_user_level', 'tabel_user.level_user = tabel_user_level.id', 'left')
                ->where('tabel_user.id', $item['user_id'])
                ->first();

            $persetujuan = $persetujuanModel
                ->where('inovasi_id', $item['id'])
                ->first();

            $inovasiData[] = [
                'id' => $item['id'],
                'judul' => $item['judul'],
                'deskripsi' => $item['deskripsi'],
                'lampiran' => $item['lampiran'] ?? null,
                'status' => $item['status'],
                'created_at' => $item['created_at'],
                'pengusul' => $pengusul->nama ?? 'Unknown',
                'photo' => $pengusul->photo ?? null,
                'grade' => $pengusul->grade ?? '-',
                'jumlah_setuju' => $jumlahSetuju,
                'jumlah_tidak_setuju' => $jumlahTidakSetuju,
                'komentar' => $komentar,
                'persetujuan' => $persetujuan
            ];
        }

        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'E-Inovasi',
            'inovasi' => $inovasiData,
            'status_filter' => $statusFilter
        ];

        return view('layout/einovasi', $data);
    }


    public function tanggapiKaru()
    {
        $inovasiId = $this->request->getPost('inovasi_id');
        $status = $this->request->getPost('status');
        $catatan = $this->request->getPost('catatan');
        $karuId = $this->session->get('user_id');

        $persetujuanModel = new PersetujuanInovasiModel();
        $persetujuanModel->save([
            'inovasi_id' => $inovasiId,
            'user_id' => $karuId,
            'status' => $status,
            'catatan' => $catatan,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $inovasiModel = new InovasiModel();
        $inovasiModel->update($inovasiId, [
            'status' => $status
        ]);

        return redirect()->to('/einovasi')->with('success', 'Persetujuan Karu berhasil disimpan.');
    }

    public function simpan()
    {
        $session = \Config\Services::session();
        $validation = \Config\Services::validation();
    
        $validation->setRules([
            'judul' => 'required|min_length[3]',
            'deskripsi' => 'required|min_length[10]',
            'lampiran' => 'permit_empty|uploaded[lampiran]|max_size[lampiran,10240]|ext_in[lampiran,pdf,doc,docx,jpg,jpeg,png]'
        ]);
    
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
    
        $file = $this->request->getFile('lampiran');
        $fileName = null;
    
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move('uploads/inovasi', $fileName);
        }
    
        $model = new \App\Models\InovasiModel();
        $model->save([
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'lampiran' => $fileName,
            'user_id' => $session->get('user_id'),
            'status' => 'diajukan',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
    
        return redirect()->to('/einovasi')->with('success', 'Saran berhasil diajukan!');
    }
    

    public function vote()
    {
        $voteModel = new VoteModel();
        $inovasiId = $this->request->getPost('inovasi_id');
        $vote = $this->request->getPost('vote');
        $userId = $this->session->user_id;

        // Cek apakah user sudah pernah vote
        $existingVote = $voteModel
            ->where(['inovasi_id' => $inovasiId, 'user_id' => $userId])
            ->first();

        if ($existingVote) {
            // Update vote yang sudah ada
            $voteModel->update($existingVote['id'], [
                'vote' => $vote,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Simpan vote baru
            $voteModel->save([
                'inovasi_id' => $inovasiId,
                'user_id' => $userId,
                'vote' => $vote,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->back()->with('success', 'Voting berhasil dikirim.');
    }

    public function kirimkomentar()
    {
        $komentar = $this->request->getPost('komentar');
        $inovasi_id = $this->request->getPost('inovasi_id');
        $session = session();
        
        // Cek jika komentar kosong
        if (!$komentar || !$inovasi_id) {
            return redirect()->back()->with('error', 'Komentar tidak boleh kosong.');
        }
        
        $data = [
            'inovasi_id' => $inovasi_id,
            'user_id' => $session->user_id,
            'komentar' => $komentar, // Ganti 'isi' dengan 'komentar'
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $komentarModel = new \App\Models\KomentarModel();
        $komentarModel->insert($data);
        
        return redirect()->back()->with('success', 'Komentar berhasil dikirim.');
    }        



}



