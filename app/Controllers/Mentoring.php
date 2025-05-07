<?php

namespace App\Controllers;

use App\Models\PrapkModel;

class Mentoring extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $model = new PrapkModel();
        $dataPrapk = $model->orderBy('no ASC, sub_no ASC')->findAll(); // urut berdasarkan no dan sub_no

        $data = [
            'level_akses' => $this->session->nama_level,
            'dtmenu' => $this->tampil_menu($this->session->level),
            'nama_menu' => 'Mentoring',
            'dataPrapk' => $dataPrapk,
        ];
        return view('layout/mentoring', $data);
    }
}
