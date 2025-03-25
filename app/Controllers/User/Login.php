<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\User_levelModel;

class Login extends BaseController
{
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->user_levelModel = new User_levelModel();
        $this->validation = \Config\Services::validation();
    }
    public function index()
    {
        session();
        if (session()->has('sdh_login')) {
            $_SESSION['color'] = 'warning';
            session()->setFlashdata('pesan', 'Anda sudah login');
            return redirect()->to('/user/profile');
        }
        $data = [
            'validation' => $this->validation
        ];
        return view('/user/login', $data);
    }
    public function register()
    {
        session();
        $data = [
            'dtlevel' => $this->user_levelModel->findAll(),
            'validation' => $this->validation
        ];
        return view('/user/register', $data);
    }

    public function ceklogin()
    {
        session();
        if (!$this->validate([
            'username' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi',
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'harus diisi',
                    'min_length' => 'minimal 5 karakter'
                ]
            ]
        ])) {
            $_SESSION['color'] = 'danger';
            session()->setFlashdata('pesan', 'Data tidak valid');
            return redirect()->to('/user/login')->withInput();
        }
        // jika validasi berhasil
        $dtlogin = $this->request->getVar();
        $user = $this->userModel->getUser($dtlogin['username'])->getResult()[0];

        if ($user) {
            if (password_verify($dtlogin['password'], $user->password)) {
                if ($user->status == 'Aktif') {
                    $dtuser = [
                        'photo' => $user->photo,
                        'nama' => $user->nama,
                        'username' => $user->username,
                        'level' => $user->level_user,
                        'nama_level' => $user->nama_level,
                        'sdh_login' => true
                    ];
                    session()->set($dtuser);
                    $_SESSION['color'] = 'success';
                    session()->setFlashdata('pesan', 'Login berhasil');
                
                    // Tambahkan redirect berdasarkan level_user
                    if ($user->level_user == 2) {
                        return redirect()->to('/dashboardppk');
                    } elseif ($user->level_user == 3) {
                        return redirect()->to('/pengadaan');
                    } elseif ($user->level_user == 4) {
                        return redirect()->to('/pengadaan');
                    } elseif ($user->level_user == 1) {
                        return redirect()->to('/admin/dashboard');
                    } else {
                        // Redirect default jika level_user tidak sesuai
                        return redirect()->to('/user/profile');
                    }
                } else {
                    $_SESSION['color'] = 'danger';
                    session()->setFlashdata('pesan', 'User belum aktif');
                    return redirect()->to('/user/login')->withInput();
                }
                
            } else {
                $_SESSION['color'] = 'danger';
                session()->setFlashdata('pesan', 'Password tidak benar');
                return redirect()->to('/user/login')->withInput();
            }
        } else {
            $_SESSION['color'] = 'danger';
            session()->setFlashdata('pesan', 'User tidak terdaftar');
            return redirect()->to('/user/login')->withInput();
        }
    }

    public function simpanregister()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Maaf Halaman Tidak Ditemukan');
        } else {
            session();
            $rules = [
                'nama' => [
                    'rules' => 'required|min_length[5]',
                    'errors' => [
                        'required' => 'harus diisi',
                        'min_length' => 'minimal 5 karakter'
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
                'password1' => [
                    'rules' => 'required|min_length[5]',
                    'errors' => [
                        'required' => 'harus diisi',
                        'min_length' => 'minimal 5 karatker'
                    ]
                ],
                'password2' => [
                    'rules' => 'required|min_length[5]',
                    'errors' => [
                        'required' => 'harus diisi',
                        'min_length' => 'minimal 5 karatker'
                    ]
                ],
                'level' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'harus diisi'
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $errors = [
                    'nama' => $this->validation->getError('nama'),
                    'username' => $this->validation->getError('username'),
                    'password1' => $this->validation->getError('password1'),
                    'password2' => $this->validation->getError('password2')
                ];
                $output = [
                    'status' => FALSE,
                    'errors' => $errors
                ];
                echo json_encode($output);
            } else {
                $pass1 = $this->request->getVar('password1');
                $pass2 = $this->request->getVar('password2');

                if ($pass1 != $pass2) {
                    $psn = 'Password dan konfirmasinya tidak sama, ulangi lagi';
                    echo json_encode(['validasi' => TRUE, 'simpan' => FALSE, 'psn' => $psn]);
                } else {
                    $password = password_hash($this->bersihkan($this->request->getVar('password1')), PASSWORD_BCRYPT);
                    $this->userModel->save([
                        'username' => $this->bersihkan($this->request->getVar('username')),
                        'password' => $password,
                        'level_user' => $this->bersihkan($this->request->getVar('level')),
                        'nama' => $this->bersihkan($this->request->getVar('nama')),
                        'photo' => 'avatar.png',
                        'status' => 'Nonaktif'
                    ]);
                    $psn = 'Selamat, Registrasi berhasil';
                    echo json_encode(['validasi' => TRUE, 'simpan' => TRUE, 'psn' => $psn]);
                }
            }
        }
    }
    public function logout()
    {
        $session = \Config\Services::session();
        $session->destroy();
        return redirect()->to('/user/login');
    }
}
