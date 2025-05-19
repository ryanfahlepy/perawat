<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;


class Dashboardkaru extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->userModel = new UserModel();
    }
    public function index()
    {
        $users = $this->userModel->getAllUsersWithLevel();
        // Ambil semua user dari DB


        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Dashboard Karu',
            'users' => $users, // Kirim ke view
        ];

        return view('layout/dashboardkaru', $data);
    }

}
