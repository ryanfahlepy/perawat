<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Models\User_levelModel;

class Manmenu extends BaseController
{
    public function __construct()
    {
        $this->menuModel = new MenuModel();
        $this->levelModel = new User_levelModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
    }
    public function index()
    {
        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Kelola Menu'
        ];
        return view('admin/kelolamenu', $data);
    }
    public function dtmenu()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'data' => $this->menuModel->getMenu()->getResult()
            ];
            $hasil = view('tabel/menu', $data);
            echo json_encode($hasil);
        } else echo "Maaf halaman tdk ditemukan";
    }
    public function form_tambah()
    {
        if ($this->request->isAJAX()) {
            $data['level'] = $this->levelModel->findAll();
            $hasil = view('admin/form_add_menu', $data);
            echo json_encode($hasil);
        } else {
            throw \CodeIgniter\Exceptions\PageNotfoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        }
    }
    public function simpan()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotfoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        } else {
            $rules = [
                'nama_menu' => [
                    'rules' => 'required|min_length[2]|is_unique[tabel_menu.nama]',
                    'errors' => [
                        'required' => 'harus diisi',
                        'min_length' => 'minimal 2 karakter',
                        'is_unique' => 'sudah terdaftar'
                    ]
                ],
                'url' => [
                    'rules' => 'required|min_length[2]',
                    'errors' => [
                        'required' => 'harus diisi',
                        'min_length' => 'minimal 2 karakter'
                    ]
                ],
                'icon' => [
                    'rules' => 'required|min_length[2]',
                    'errors' => [
                        'required' => 'harus diisi',
                        'min_length' => 'minimal 2 karakter'
                    ]
                ],
                'level_id' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'harus diisi'
                    ]
                ],
                'urutan' => [
                    'rules' => 'required|is_natural_no_zero',
                    'errors' => [
                        'required' => 'harus diisi',
                        'is_natural_no_zero' => 'harus angka bulat'
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $errors = [
                    'nama_menu' => $this->validation->getError('nama_menu'),
                    'url' => $this->validation->getError('url'),
                    'icon' => $this->validation->getError('icon'),
                    'level_id' => $this->validation->getError('level_id'),
                    'urutan' => $this->validation->getError('urutan')
                ];
                $output = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($output);
            } else {
                $this->menuModel->save([
                    'nama' => $this->bersihkan($this->request->getVar('nama_menu')),
                    'url' => $this->bersihkan($this->request->getVar('url')),
                    'icon' => $this->bersihkan($this->request->getVar('icon')),
                    'user_level_id' => $this->bersihkan($this->request->getVar('level_id')),
                    'urutan' => $this->bersihkan($this->request->getVar('urutan')),
                    'aktif' => '0'
                ]);
                $psn = 'Menu berhasil disimpan';
                echo json_encode(['status' => TRUE, 'psn' => $psn]);
            }
        }
    }
    public function form_edit()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id');
            $data['level'] = $this->levelModel->findAll();
            $data['data'] = $this->menuModel->find($id);
            $hasil = view('admin/form_edit_menu', $data);
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
            $id = $this->request->getVar('id_menu');
            $namaLama = $this->menuModel->find($id)->nama;
            $rule = ($this->request->getVar('nama_menu') == $namaLama) ? 'required|min_length[2]' : 'required|min_length[2]|is_unique[tabel_menu.nama]';
            $rules = [
                'nama_menu' => [
                    'rules' => $rule,
                    'errors' => [
                        'required' => 'harus diisi',
                        'min_length' => 'minimal 2 karakter',
                        'is_unique' => 'sudah terdaftar'
                    ]
                ],
                'url' => [
                    'rules' => 'required|min_length[2]',
                    'errors' => [
                        'required' => 'harus diisi',
                        'min_length' => 'minimal 2 karakter'
                    ]
                ],
                'icon' => [
                    'rules' => 'required|min_length[2]',
                    'errors' => [
                        'required' => 'harus diisi',
                        'min_length' => 'minimal 2 karakter'
                    ]
                ],
                'level_id' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'harus diisi'
                    ]
                ],
                'urutan' => [
                    'rules' => 'required|is_natural_no_zero',
                    'errors' => [
                        'required' => 'harus diisi',
                        'is_natural_no_zero' => 'harus angka bulat'
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $errors = [
                    'nama_menu' => $this->validation->getError('nama_menu'),
                    'url' => $this->validation->getError('url'),
                    'icon' => $this->validation->getError('icon'),
                    'level_id' => $this->validation->getError('level_id'),
                    'urutan' => $this->validation->getError('urutan')
                ];
                $output = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($output);
            } else {
                $dtEdit = [
                    'nama' => $this->bersihkan($this->request->getVar('nama_menu')),
                    'url' => $this->bersihkan($this->request->getVar('url')),
                    'icon' => $this->bersihkan($this->request->getVar('icon')),
                    'user_level_id' => $this->bersihkan($this->request->getVar('level_id')),
                    'urutan' => $this->bersihkan($this->request->getVar('urutan')),
                    'aktif' => $this->menuModel->find($id)->aktif
                ];
                $this->menuModel->update($id, $dtEdit);
                $psn = 'Menu berhasil diupdate, untuk melihat efeknya silahkan refresh';
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
            $this->menuModel->delete($id);
            $psn = 'Menu berhasil dihapus, untuk melihat efeknya silahkan refresh';
            echo json_encode(['status' => TRUE, 'psn' => $psn]);
        }
    }
    public function nonaktif()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotfoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        } else {
            $id = $this->request->getVar('id');
            $menu = $this->menuModel->find($id);
            if ($menu->nama == 'Kelola Menu') {
                $psn = 'Kelola Menu tidak boleh dinonaktifkan';
                echo json_encode(['status' => TRUE, 'psn' => $psn]);
            } else {
                unset($menu->aktif);
                if (!isset($menu->aktif)) {
                    $menu->aktif = '0';
                }
                $this->menuModel->save($menu);
                $psn = 'Menu berhasil dinonaktifkan, untuk melihat efeknya silahkan refresh';
                echo json_encode(['status' => TRUE, 'psn' => $psn]);
            }
        }
    }
    public function aktif()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotfoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        } else {
            $id = $this->request->getVar('id');
            $menu = $this->menuModel->find($id);
            unset($menu->aktif);
            if (!isset($menu->aktif)) {
                $menu->aktif = '1';
            }
            $this->menuModel->save($menu);
            $psn = 'Menu berhasil diaktifkan, untuk melihat efeknya silahkan refresh';
            echo json_encode(['status' => TRUE, 'psn' => $psn]);
        }
    }
}
