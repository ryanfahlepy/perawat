<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\User_levelModel;

class Manuser extends BaseController
{
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->levelModel = new User_levelModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
    }
    public function index()
    {
        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Kelola User'
        ];
        return view('admin/kelolauser', $data);
    }
    public function dtuser()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'data' => $this->userModel->getUser()->getResult()
            ];
            $hasil = view('tabel/user', $data);
            echo json_encode($hasil);
        } else echo "Maaf halaman tidak ditemukan";
    }
    public function form_tambah()
    {
        if ($this->request->isAJAX()) {
            $data['level'] = $this->levelModel->findAll();
            $hasil = view('admin/form_add_user', $data);
            echo json_encode($hasil);
        } else {
            throw \CodeIgniter\Exceptions\PageNotfoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        }
    }
    public function form_edit()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id');
            $data = [
                'level' => $this->levelModel->findAll(),
                'data' => $this->userModel->find($id)
            ];
            $hasil = view('admin/form_edit_user', $data);
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
                'nama' => [
                    'rules' => 'required|min_length[2]',
                    'errors' => [
                        'required' => 'harus disi',
                        'min_length' => 'minimal 2 karakter',
                    ]
                ],
                'username' => [
                    'rules' => 'required|min_length[2]|is_unique[tabel_user.username]',
                    'errors' => [
                        'required' => 'harus disi',
                        'min_length' => 'minimal 2 karakter',
                        'is_unique' => 'username sudah terdaftar',
                        
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[2]',
                    'errors' => [
                        'required' => 'harus disi',
                        'min_length' => 'minimal 2 karakter',
                    ]
                ],
                'level_id' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'harus disi'
                    ]
                ],
                'photo' => [
                    'rules' => 'uploaded[photo]|is_image[photo]|mime_in[photo,image/png,image/jpg,image/jpeg]|max_size[photo,1024]',
                    'errors' => [
                        'uploaded' => 'photo belum diupload',
                        'is_image' => 'bukan file gambar',
                        'mime_in' => 'file harus extensi .png /.jpg /.jpeg ',
                        'max_size' => 'ukuran file maximal 1 GB'
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                $errors = [
                    'nama' => $this->validation->getError('nama'),
                    'username' => $this->validation->getError('username'),
                    'password' => $this->validation->getError('password'),
                    'level_id' => $this->validation->getError('level_id'),
                    'photo' => $this->validation->getError('photo')
                ];
                $output = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($output);
            } else {
                $filePhoto = $this->request->getFile('photo');
                $photo = $filePhoto->getRandomName();
                $filePhoto->move('assets/dist/img/user', $photo);
                $this->userModel->save([
                    'nama' => $this->bersihkan($this->request->getVar('nama')),
                    'username' => $this->bersihkan($this->request->getVar('username')),
                    'password' => password_hash($this->bersihkan($this->request->getVar('password')), PASSWORD_BCRYPT),
                    'level_user' => $this->bersihkan($this->request->getVar('level_id')),
                    'photo' => $photo,
                    'status' => 'Nonaktif'
                ]);
                $psn = 'User berhasil disimpan';
                echo json_encode(['status' => TRUE, 'psn' => $psn]);
            }
        }
    }
    public function update()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotfoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        } else {
            $id = $this->request->getVar('id_user');
            $dtuser = $this->userModel->find($id);
            $usernameLama = $dtuser->username;
            $rule = ($this->request->getVar('username') == $usernameLama) ? 'required|min_length[3]' : 'required|min_length[3]|is_unique[tabel_user.username]';
            $rules = [
                'nama' => [
                    'rules' => 'required|min_length[2]',
                    'errors' => [
                        'required' => 'harus disi',
                        'min_length' => 'minimal 2 karakter',
                    ]
                ],
                'username' => [
                    'rules' => $rule,
                    'errors' => [
                        'required' => 'harus disi',
                        'min_length' => 'minimal 2 karakter',
                        'is_unique' => 'username sudah terdaftar',
                    
                    ]
                ],
                'level_id' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'harus disi'
                    ]
                ],
                'photo' => [
                    'rules' => 'is_image[photo]|mime_in[photo,image/png,image/jpg,image/jpeg]|max_size[photo,1024]',
                    'errors' => [
                        'is_image' => 'bukan file gambar',
                        'mime_in' => 'file harus extensi .png /.jpg /.jpeg ',
                        'max_size' => 'ukuran file maximal 1 GB'
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                $errors = [
                    'nama' => $this->validation->getError('nama'),
                    'username' => $this->validation->getError('username'),
                    'level_id' => $this->validation->getError('level_id'),
                    'photo' => $this->validation->getError('photo')
                ];
                $output = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($output);
            } else {
                $photoLama = $this->userModel->find($id)->photo;
                // cek apakah ada file photo yg diupload
                $filePhoto = $this->request->getFile('photo');
                if ($filePhoto->getError() == 4) {
                    $photo = $photoLama;
                } else {
                    $filePhoto = $this->request->getFile('photo');
                    $photo = $filePhoto->getRandomName();
                    $filePhoto->move('assets/dist/img/user', $photo);
                }
                $dtedit = [
                    'photo' => $photo,
                    'nama' => $this->bersihkan($this->request->getVar('nama')),
                    'username' => $this->bersihkan($this->request->getVar('username')),
                    'password' => $dtuser->password,
                    'level_user' => $this->bersihkan($this->request->getVar('level_id')),
                    'status' => $this->userModel->find($id)->status
                ];
                $this->userModel->update($id, $dtedit);
                $psn = 'User berhasil disimpan';
                echo json_encode(['status' => TRUE, 'psn' => $psn]);
            }
        }
    }
    public function hal_resset_psswrd()
    {
        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Reset Password'
        ];
        return view('user/hal_resset', $data);
    }
    public function freset_password()
    {
        if ($this->request->isAJAX()) {
            $data['data'] = $this->userModel->getUser($this->session->username)->getResult();
            $hasil = view('user/reset_passwrd', $data);
            echo json_encode($hasil);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        }
    }
    public function notif_reset_password()
    {
        if ($this->request->isAJAX()) {
            $hasil = view('user/notif_passwrd');
            echo json_encode($hasil);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        }
    }
    public function reset_password()
    {
        if ($this->request->isAJAX()) {
            $rules = [
                'pss1' => [
                    'rules' => 'required|min_length[5]',
                    'errors' => [
                        'required' => 'harus disi',
                        'min_length' => 'minimal 5 karakter'
                    ]
                ],
                'pss2' => [
                    'rules' => 'required|min_length[5]',
                    'errors' => [
                        'required' => 'harus disi',
                        'min_length' => 'minimal 5 karakter',
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $errors = [
                    'pss1' => $this->validation->getError('pss1'),
                    'pss2' => $this->validation->getError('pss2')
                ];
                $output = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($output);
            } else {
                if ($this->request->getVar('pss1') == $this->request->getVar('pss2')) {
                    $id = $this->request->getVar('id');
                    $pssbaru = password_hash($this->bersihkan($this->request->getVar('pss1')), PASSWORD_BCRYPT);
                    $user = $this->userModel->find($id);
                    unset($user->password);
                    if (!isset($user->password)) {
                        $user->password = $pssbaru;
                    }
                    $this->userModel->save($user);
                    $psn = 'Password berhasil diupdate';
                    echo json_encode(['status' => TRUE, 'res_pss' => TRUE, 'psn' => $psn]);
                } else {
                    $psn = 'Password gagal direset, password & konfirmasinya tidak sama, silahkan ulangi lagi ';
                    echo json_encode(['status' => TRUE, 'res_pss' => FALSE, 'psn' => $psn]);
                }
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        }
    }
    public function nonaktif()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id');
            $user = $this->userModel->find($id);
            if ($user->username == 'admin') {
                $psn = 'Data user admin tdk bisa dinonaktifkan';
                echo json_encode(['status' => TRUE, 'psn' => $psn]);
            } else {
                unset($user->status);
                if (!isset($user->status)) {
                    $user->status = 'Nonaktif';
                }
                $this->userModel->save($user);
                $psn = 'User berhasil dinonaktifkan';
                echo json_encode(['status' => TRUE, 'psn' => $psn]);
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        }
    }
    public function aktif()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id');
            $user = $this->userModel->find($id);
            unset($user->status);
            if (!isset($user->status)) {
                $user->status = 'Aktif';
            }
            $this->userModel->save($user);
            $psn = 'User berhasil diaktifkan';
            echo json_encode(['status' => TRUE, 'psn' => $psn]);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        }
    }
    public function hapus()
    {
        if ($this->request->isAJAX()) {
            $nama = $this->userModel->find($this->request->getVar('id'))->username;
            if ($nama == 'admin') {
                $psn = 'User admin tidak boleh dihapus';
                echo json_encode(['status' => TRUE, 'psn' => $psn]);
            } else {
                $this->userModel->delete($this->request->getVar('id'));
                $psn = 'User berhasil dihapus';
                echo json_encode(['status' => TRUE, 'psn' => $psn]);
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        }
    }
}
