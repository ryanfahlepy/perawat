<?php

namespace App\Controllers\Pokja;

use App\Controllers\BaseController;

class Dashboardpokja extends BaseController
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
            'nama_menu' => 'Dashboard Pokja'
        ];
        return view('pokja/dashboardpokja', $data);
    }
}
