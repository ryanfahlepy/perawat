<?php


namespace App\Controllers;



class Pelatihan extends BaseController
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
            'nama_menu' => 'Pelatihan',
        ];
        return view('layout/pelatihan', $data
        );
    }

}