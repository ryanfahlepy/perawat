<?php

namespace App\Controllers\Ketuapokja;

use App\Controllers\BaseController;

class Dashboardketuapokja extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
    }
    public function index()
    {
        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Dashboard Ketua Pokja'
        ];
        return view('ketuapokja/dashboardketuapokja', $data);
    }
}
