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

        $this->visiMisiModel->upsertVisiMisi($data['jenis_konten'], $data['isi_konten']);
        $this->redirect('/admin/visimisi');
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
