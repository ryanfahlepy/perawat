<?php

namespace App\Controllers\Pokja;

use App\Controllers\BaseController;

class Home extends BaseController
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
            'nama_menu' => 'Menu Pokja'
        ];
        return view('pokja/halawal', $data);
    }
}
