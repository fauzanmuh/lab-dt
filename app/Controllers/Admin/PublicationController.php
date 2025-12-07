<?php

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Pagination;
use App\Models\Publication;
use App\Models\Member;

class PublicationController extends Controller
{
    protected $publicationModel;
    protected $memberModel;

    public function __construct()
    {
        $this->publicationModel = $this->loadModel(Publication::class);
        $this->memberModel = $this->loadModel(Member::class);
    }

    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $total = $this->publicationModel->countAllPublications();
        $pagination = new Pagination($total, $limit, $page);

        $publications = $this->publicationModel->getPaginatedPublications($limit, $pagination->getOffset());
        $members = $this->memberModel->getAllMembers(); // For the "Author" dropdown in modal

        return $this->view('admin/publications/index', [
            'publications' => $publications,
            'members' => $members,
            'pagination' => $pagination,
            'baseUrl' => '/admin/publications',
            'pageTitle' => 'Manajemen Publikasi',
            'layout' => 'layouts/admin'
        ]);
    }

    public function store()
    {
        $data = $_POST;

        // In a real app, id_anggota might come from session if not admin
        // For admin, we allow selecting the author

        // Restrict operators from setting status
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $data['status'] = 'pending';
        }

        $this->publicationModel->createPublication($data);
        $_SESSION['flash_success'] = 'Publikasi berhasil ditambahkan.';
        $this->redirect('/admin/publications');
    }

    public function update($id)
    {
        $data = $_POST;

        // Restrict operators from changing status, and reset to pending on edit
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $data['status'] = 'pending';
        }

        $this->publicationModel->updatePublication($id, $data);
        $_SESSION['flash_success'] = 'Publikasi berhasil diperbarui.';
        $this->redirect('/admin/publications');
    }

    public function destroy($id)
    {
        $this->publicationModel->deletePublication($id);
        $_SESSION['flash_success'] = 'Publikasi berhasil dihapus.';
        $this->redirect('/admin/publications');
    }
}
