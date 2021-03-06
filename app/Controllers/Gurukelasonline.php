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

    public function dashboard()
    {
        $data = [
            'judul' => 'Kelas Online',
            'gurukelasonline' => $this->M_Gurukelasonline->loadData(),
        ];

        echo view('templates/v_header', $data);
        echo view('templates/v_sidebar');
        echo view('templates/v_topbar');
        echo view('gurukelasonline/dashboard');
        echo view('templates/v_footer');
    }

    public function kelas($_id_kelasonline)
    {
        $data = [
            'judul' => 'Kelas Online',
            'materi' => $this->M_Gurukelasonline->loadDataMateri($_id_kelasonline),
            'kelas' => $this->M_Gurukelasonline->loadDataKelas($_id_kelasonline),
        ];

        $data['id_kelasonline'] = $_id_kelasonline;
        echo view('templates/v_header', $data);
        echo view('templates/v_sidebar');
        echo view('templates/v_topbar');
        echo view('gurukelasonline/kelas', $data);
        echo view('templates/v_footer');
    }

    public function akelas($_id_kelasonline)
    {
        $data = [
            'judul' => 'Kelas Online',
            'materi' => $this->M_Gurukelasonline->loadDataMateri($_id_kelasonline),
            'kelas' => $this->M_Gurukelasonline->loadDataKelas($_id_kelasonline),
        ];

        $data['id_kelasonline'] = $_id_kelasonline;
        echo view('templates/v_header', $data);
        echo view('templates/v_sidebar');
        echo view('templates/v_topbar');
        echo view('gurukelasonline/akelas', $data);
        echo view('templates/v_footer');
    }

    public function tambahmateri($_id_kelasonline)
    {
        $data = [
            'judul' => 'Materi Kelas Online',
            'gurukelasonline' => $this->M_Gurukelasonline->loadData(),
        ];

        session()->set('id_kelasonline', $_id_kelasonline);

        echo view('templates/v_header', $data);
        echo view('templates/v_sidebar');
        echo view('templates/v_topbar');
        echo view('gurukelasonline/tambahmateri', $data);
        echo view('templates/v_footer');
    }

    public function tambahmaterikelas()
    {
        $id_kelasonline = session()->get('id_kelasonline');
        if ($this->validate([
            'id_kelasonline' => [
                'label' => 'Kelas Online',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.',
                ]
            ],
            'judul' => [
                'label' => 'Judul',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.',
                ]
            ],

        ])) {
            $file = $this->request->getFile('file');
            if ($file->getError() == 4) {
                $data = [
                    'id_kelasonline' =>  $this->request->getPost('id_kelasonline'),
                    'judul' => $this->request->getPost('judul'),
                    'deskripsi' => $this->request->getPost('deskripsi'),
                ];
                $this->M_Gurukelasonline->tambahmateri($data);
            } else {
                $nama_file = $file->getClientName();
                $data = [
                    'id_kelasonline' =>  $this->request->getPost('id_kelasonline'),
                    'judul' => $this->request->getPost('judul'),
                    'deskripsi' => $this->request->getPost('deskripsi'),
                    'file' => $nama_file,
                ];
                $this->M_Gurukelasonline->tambahmateri($data);
                $file->move('materi tugas', $nama_file);
            }
            session()->setFlashdata('message', 'Di Tambahkan');
            return redirect()->to(base_url('gurukelasonline/kelas/' . $id_kelasonline));
        } else {
            session()->setFlashData('validationguruerror', \Config\Services::validation()->listErrors());
            return redirect()->to(base_url('gurukelasonline/tambahmateri'))->withInput();
        }
    }

    public function edit($id)
    {
        $data = [
            'judul' => 'Materi Kelas Online',
            'materi' => $this->M_Gurukelasonline->detailData($id),
        ];

        echo view('templates/v_header', $data);
        echo view('templates/v_sidebar');
        echo view('templates/v_topbar');
        echo view('gurukelasonline/editmateri');
        echo view('templates/v_footer');
    }

    public function editmaterikelas($id)
    {
        $materi = $this->M_Gurukelasonline->detailData($id);
        $file = $this->request->getFile('file');
        if ($file->getError() == 4) {
            $data = [
                'id_materi' => $id,
                'judul' => $this->request->getPost('judul'),
                'deskripsi' => $this->request->getPost('deskripsi'),
                // 'file' => '',
            ];
            $this->M_Gurukelasonline->edit($data);
        } else {
            $materi = $this->M_Gurukelasonline->detailData($id);
            if ($materi['file'] != "") {
                unlink('materi tugas/' . $materi['file']);
            }
            $nama_file = $file->getClientName();
            $data = [
                'id_materi' => $id,
                'judul' => $this->request->getPost('judul'),
                'deskripsi' => $this->request->getPost('deskripsi'),
                'file' => $nama_file,
            ];
            $file->move('materi tugas', $nama_file);
            $this->M_Gurukelasonline->edit($data);
        }
        session()->setFlashdata('message', 'Di Ubah');
        return redirect()->to(base_url('gurukelasonline/edit/' . $id));
    }

    public function hapus($id)
    {
        $data = [
            'id_materi' => $id
        ];

        $this->M_Gurukelasonline->hapus($data);
        session()->setFlashdata('message', 'Di Hapus');
        return redirect()->to(base_url('gurukelasonline/kelas/' . $id));
    }

    public function viewpdf($id)
    {
        $data = [
            'judul' => 'Preview PDF',
            'materi' => $this->M_Gurukelasonline->detailPDF($id),
        ];

        echo view('templates/v_header', $data);
        echo view('Gurukelasonline/viewpdf');
    }
    public function viewjpdf($id)
    {
        $data = [
            'judul' => 'Preview PDF',
            'jawaban' => $this->M_Gurukelasonline->detailjawabanPDF($id),
        ];

        echo view('templates/v_header', $data);
        echo view('Gurukelasonline/viewjpdf', $data);
    }

    public function jtugas($id_materinya)
    {
        session()->set('id_materi', $id_materinya);
        $data = [
            'judul' => 'Jawaban Tugas Kelas Online',
            'jawabantugas' => $this->M_Gurukelasonline->loadDataJawabanTugas($id_materinya),
        ];

        echo view('templates/v_header', $data);
        echo view('templates/v_sidebar');
        echo view('templates/v_topbar');
        echo view('gurukelasonline/jtugas', $data);
        echo view('templates/v_footer');
    }
    public function presensi($id_presensinya)
    {
        session()->set('id_materi', $id_presensinya);
        $data = [
            'judul' => 'Presensi Kelas Online',
            'presensi' => $this->M_Gurukelasonline->loadDataPresensi($id_presensinya),
        ];
        
        echo view('templates/v_header', $data);
        echo view('templates/v_sidebar');
        echo view('templates/v_topbar');
        echo view('gurukelasonline/presensi', $data);
        echo view('templates/v_footer');
    }
}
