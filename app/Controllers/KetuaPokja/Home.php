<?php

namespace App\Controllers\KetuaPokja;

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
            'nama_menu' => 'Menu Ketua Pokja'
        ];
        return view('ketuapokja/halawal', $data);
    }
}
