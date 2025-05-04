<?php


namespace App\Controllers;



class Kompetensi extends BaseController
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
            'nama_menu' => 'E-Kinerja',
        ];
        return view('layout/kompetensi', $data
        );
    }

}