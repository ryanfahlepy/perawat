<?php

namespace App\Controllers\Lpse;

use App\Controllers\BaseController;

class Templatedokumen extends BaseController
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
            'nama_menu' => 'Template Dokumen'
        ];
        return view('lpse/templatedokumen', $data);
    }
}
