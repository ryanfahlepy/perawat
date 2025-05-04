<?php


namespace App\Controllers;



class Mentoring extends BaseController
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
            'nama_menu' => 'Mentoring',
        ];
        return view('layout/mentoring', $data
        );
    }

}