<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\VisiMisi;

class VisiMisiController extends Controller
{
    protected $visiMisiModel;

    public function __construct()
    {
        $this->visiMisiModel = $this->loadModel(VisiMisi::class);
    }

    public function index()
    {
        $visiMisi = $this->visiMisiModel->getAllVisiMisi();
        return $this->view('admin/visimisi/index', ['visiMisi' => $visiMisi, 'pageTitle' => 'Vision & Mission Management', 'layout' => 'layouts/admin']);
    }

    public function store()
    {
        $data = $_POST;

        // Tambahkan pengecekan data untuk menghindari Notice PHP
        if (isset($data['jenis_konten']) && isset($data['isi_konten'])) {
            // Model sekarang menerima data yang benar
            $this->visiMisiModel->upsertVisiMisi($data['jenis_konten'], $data['isi_konten']);
            // Redirect kembali ke halaman manajemen (konsisten dengan controller lain)
            $this->redirect('/admin/visimisi');
            return;
        }

        // Opsional: respons error jika data tidak lengkap
        $this->redirect('/admin/visimisi');
        return;
    }

    public function update($id)
    {
        $data = $_POST;

        $this->visiMisiModel->updateVisiMisi($id, $data);
        $this->redirect('/admin/visimisi');
    }

    public function destroy($id)
    {
        $this->visiMisiModel->deleteVisiMisi($id);
        $this->redirect('/admin/visimisi');
    }
}
