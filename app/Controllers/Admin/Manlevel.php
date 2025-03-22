<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\User_levelModel;

class Manlevel extends BaseController
{
    public function __construct()
    {
        $this->user_levelModel = new User_levelModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
    }
    public function index()
    {
        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Master Level'
        ];
        return view('admin/kelolalevel', $data);
    }
    public function dtlevel()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'data' => $this->user_levelModel->findAll()
            ];
            $hasil = view('tabel/level', $data);
            echo json_encode($hasil);
        } else {
            throw \CodeIgniter\Exceptions\PageNotfoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        }
    }
    public function form_tambah()
    {
        if ($this->request->isAJAX()) {
            $hasil = view('modal/form_add_level');
            echo json_encode($hasil);
        } else {
            throw \CodeIgniter\Exceptions\PageNotfoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        }
    }
    public function simpan()
    {
        $rules = [
            'nama_level' => [
                'rules' => 'required|min_length[2]|is_unique[tabel_user_level.nama_level]',
                'errors' => [
                    'required' => 'harus disi',
                    'min_length' => 'minimal 2 karakter',
                    'is_unique' => 'sudah terdaftar'
                ]
            ],
            'keterangan' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'harus disi',
                    'min_length' => 'minimal 5 karakter',
                ]
            ]
        ];
        if (!$this->validate($rules)) {
            $errors = [
                'nama_level' => $this->validation->getError('nama_level'),
                'keterangan' => $this->validation->getError('keterangan')
            ];
            $output = [
                'status' => FALSE,
                'errors' => $errors
            ];
            echo json_encode($output);
        } else {
            $this->user_levelModel->save([
                'nama_level' => $this->bersihkan($this->request->getVar('nama_level')),
                'keterangan' => $this->bersihkan($this->request->getVar('keterangan'))
            ]);
            $psn = 'Level berhasil disimpan, untuk melihat efeknya silahkan refresh';
            echo json_encode(['status' => TRUE, 'psn' => $psn]);
        }
    }
    public function form_edit()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id');
            $data['data'] = $this->user_levelModel->find($id);
            $hasil = view('modal/form_edit_level', $data);
            echo json_encode($hasil);
        } else {
            throw \CodeIgniter\Exceptions\PageNotfoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        }
    }
    public function update()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotfoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        } else {
            $id = $this->request->getVar('id_level');
            $namaLama = $this->user_levelModel->find($id)->nama_level;
            $rule = ($this->request->getVar('nama_level') == $namaLama) ? 'required|min_length[2]' : 'required|min_length[2]|is_unique[tabel_user_level.nama_level]';
            $rules = [
                'nama_level' => [
                    'rules' => $rule,
                    'errors' => [
                        'required' => 'harus disi',
                        'min_length' => 'minimal 2 karakter',
                        'is_unique' => 'sudah terdaftar'
                    ]
                ],
                'keterangan' => [
                    'rules' => 'required|min_length[5]',
                    'errors' => [
                        'required' => 'harus disi',
                        'min_length' => 'minimal 5 karakter',
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $errors = [
                    'nama_level' => $this->validation->getError('nama_level'),
                    'keterangan' => $this->validation->getError('keterangan')
                ];
                $output = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($output);
            } else {
                $dtEdit = [
                    'nama_level' => $this->bersihkan($this->request->getVar('nama_level')),
                    'keterangan' => $this->bersihkan($this->request->getVar('keterangan'))
                ];
                $this->user_levelModel->update($id, $dtEdit);
                $psn = 'Level berhasil diupdate';
                echo json_encode(['status' => TRUE, 'psn' => $psn]);
            }
        }
    }
    public function hapus()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotfoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        } else {
            $id = $this->request->getVar('id');
            $this->user_levelModel->delete($id);
            $psn = 'Data Level berhasil dihapus';
            echo json_encode(['status' => TRUE, 'psn' => $psn]);
        }
    }
}
