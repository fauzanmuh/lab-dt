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
        return $this->view('admin/visimisi/index', ['visiMisi' => $visiMisi, 'pageTitle' => 'Manajemen Visi Misi', 'layout' => 'layouts/admin']);
    }

    public function store()
    {
        $data = $_POST;

        // Handle standard form submission (visi and misi)
        if (isset($data['visi'])) {
            $this->visiMisiModel->upsertVisiMisi('visi', $data['visi']);
        }
        if (isset($data['misi'])) {
            $this->visiMisiModel->upsertVisiMisi('misi', $data['misi']);
        }

        // Set flash message
        $_SESSION['flash_success'] = 'Visi dan Misi berhasil diperbarui.';

        // Redirect back
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
