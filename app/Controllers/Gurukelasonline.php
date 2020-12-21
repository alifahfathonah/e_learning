<?php

namespace App\Controllers;

use App\Models\M_Gurukelasonline;
use App\Models\M_Kelasonline;
use App\Models\M_Guru;
use App\Models\M_Mapel;
use App\Models\M_Kelas;

class Gurukelasonline extends BaseController
{
    public function __construct()
    {
        helper('form');
        $this->M_Gurukelasonline = new M_Gurukelasonline();
        $this->M_Kelasonline = new M_Kelasonline();
        $this->M_Guru = new M_Guru();
        $this->M_Mapel = new M_Mapel();
        $this->M_Kelas = new M_Kelas();
    }
    public function index()
    {
        $data = [
            'judul' => 'Kelas Online',
            'gurukelasonline' => $this->M_Gurukelasonline->loadData(),
        ];

        echo view('templates/v_header', $data);
        echo view('templates/v_sidebar');
        echo view('templates/v_topbar');
        echo view('gurukelasonline/index');
        echo view('templates/v_footer');
    }

    public function kelas($_id_kelasonline)
    {
        $n_mapel = new M_Gurukelasonline();
        $data = [
            'judul' => 'Kelas Online',
            'materi' => $this->M_Gurukelasonline->loadDataMateri($_id_kelasonline),
        ];
        $data['id_kelasonline'] = $_id_kelasonline;
        $data['mapel'] = $n_mapel->loadDataMateri($_id_kelasonline);
        $_id_kelasonline = session()->set('id_kelasonline', $_id_kelasonline);
        echo view('templates/v_header', $data);
        echo view('templates/v_sidebar');
        echo view('templates/v_topbar');
        echo view('gurukelasonline/kelas', $data);
        echo view('templates/v_footer');
    }

    public function tambahmateri()
    {

        $data = [
            'judul' => 'Materi Kelas Online',
            'gurukelasonline' => $this->M_Gurukelasonline->loadData(),
        ];
        // $data['id_kelas'] = $id_kelasonline;

        echo view('templates/v_header', $data);
        echo view('templates/v_sidebar');
        echo view('templates/v_topbar');
        echo view('gurukelasonline/tambahmateri');
        echo view('templates/v_footer');
    }

    public function tambahmaterikelas()
    {
        // $id_kelasonline = session()->get('id_kelasonline');
        // $tambah_materi = new M_Gurukelasonline();
        // $deskripsi = $this->request->getPost('deskripsi');
        // $file =  $this->request->getPost('file');
        // $file = $this->request->getFile('file');
        // $nama_file = $file->getClientName();
        $data = [
            'id_kelasonline' =>  $this->request->getPost('id_kelasonline'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            // 'file' => $nama_file,
        ];
        $this->M_Gurukelasonline->tambahmateri($data);
        // $file->move('materi tugas', $nama_file);
        // $tambah_isi_materi = $tambah_materi->tambahmateri($id_kelasonline, $deskripsi, $file);

        session()->setFlashdata('message', 'Di Tambahkan');
        return redirect()->to(base_url('gurukelasonline/tambahmateri'));
    }

    public function hapus($id)
    {
        $data = [
            'id_materi' => $id
        ];
        $this->M_Kelasonline->hapus($data);
        session()->setFlashdata('message', 'Di Hapus');
        return redirect()->to(base_url('kelasonline'));
    }
}