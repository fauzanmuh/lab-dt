<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Publication;


class ApprovalController extends Controller
{
    protected $newsModel;
    protected $galleryModel;
    protected $publicationModel;

    public function __construct()
    {
        // Restrict access to admins only
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/admin/dashboard');
            exit;
        }

        $this->newsModel = $this->loadModel(News::class);
        $this->galleryModel = $this->loadModel(Gallery::class);
        $this->publicationModel = $this->loadModel(Publication::class);
    }

    public function index()
    {

        // News
        $allNews = $this->newsModel->getAllNews();
        $pendingNews = array_filter($allNews, function ($item) {
            return $item['status'] === 'pending';
        });

        // Gallery
        $allGallery = $this->galleryModel->getAllPhotos();
        $pendingGallery = array_filter($allGallery, function ($item) {
            return $item['status'] === 'pending';
        });

        // Publications
        $allPublications = $this->publicationModel->getAllPublications();
        $pendingPublications = array_filter($allPublications, function ($item) {
            return $item['status'] === 'pending';
        });

        return $this->view('admin/approvals/index', [
            'pendingNews' => $pendingNews,
            'pendingGallery' => $pendingGallery,
            'pendingPublications' => $pendingPublications,
            'pageTitle' => 'Pending Approvals',
            'layout' => 'layouts/admin'
        ]);
    }

    public function approve($type, $id)
    {
        $adminId = $_SESSION['user']['id_anggota'] ?? null;

        switch ($type) {
            case 'news':
                $this->newsModel->updateNews($id, ['status' => 'approved', 'id_admin_penilai' => $adminId]);
                break;
            case 'gallery':
                $this->galleryModel->updatePhoto($id, ['status' => 'approved', 'id_admin_penilai' => $adminId]);
                break;
            case 'publication':
                $this->publicationModel->updatePublication($id, ['status' => 'approved', 'id_admin_penilai' => $adminId]);
                break;
        }

        $this->redirect('/admin/approvals');
    }

    public function reject($type, $id)
    {
        $adminId = $_SESSION['user']['id_anggota'] ?? null;
        $note = $_POST['catatan_admin'] ?? null;

        switch ($type) {
            case 'news':
                $this->newsModel->updateNews($id, ['status' => 'rejected', 'id_admin_penilai' => $adminId, 'catatan_admin' => $note]);
                break;
            case 'gallery':
                $this->galleryModel->updatePhoto($id, ['status' => 'rejected', 'id_admin_penilai' => $adminId, 'catatan_admin' => $note]);
                break;
            case 'publication':
                $this->publicationModel->updatePublication($id, ['status' => 'rejected', 'id_admin_penilai' => $adminId, 'catatan_admin' => $note]);
                break;
        }

        $this->redirect('/admin/approvals');
    }
}
