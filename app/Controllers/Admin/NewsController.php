<?php

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Pagination;
use App\Models\News;
use App\Models\Member;

class NewsController extends Controller
{
    protected $newsModel;
    protected $memberModel;

    public function __construct()
    {
        $this->newsModel = $this->loadModel(News::class);
        $this->memberModel = $this->loadModel(Member::class);
    }

    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $total = $this->newsModel->countAllNews();
        $pagination = new Pagination($total, $limit, $page);

        $news = $this->newsModel->getPaginatedNews($limit, $pagination->getOffset());
        $members = $this->memberModel->getAllMembers();

        return $this->view('admin/news/index', [
            'news' => $news,
            'members' => $members,
            'pagination' => $pagination,
            'baseUrl' => '/admin/news',
            'pageTitle' => 'Manajemen Berita',
            'layout' => 'layouts/admin'
        ]);
    }

    public function store()
    {
        $data = $_POST;

        // Handle File Upload
        if (isset($_FILES['gambar_utama']) && $_FILES['gambar_utama']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../public/uploads/news/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES['gambar_utama']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['gambar_utama']['tmp_name'], $targetPath)) {
                $data['gambar_utama'] = 'uploads/news/' . $fileName;
            }
        }

        // Restrict operators from setting status
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $data['status'] = 'pending';
        }

        $this->newsModel->createNews($data);
        $_SESSION['flash_success'] = 'Berita berhasil ditambahkan.';
        $this->redirect('/admin/news');
    }

    public function update($id)
    {
        $data = $_POST;

        // Restrict operators from changing status, and reset to pending on edit
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $data['status'] = 'pending';
        }

        // Handle File Upload (if new image provided)
        if (isset($_FILES['gambar_utama']) && $_FILES['gambar_utama']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../public/uploads/news/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES['gambar_utama']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['gambar_utama']['tmp_name'], $targetPath)) {
                $data['gambar_utama'] = 'uploads/news/' . $fileName;

                // Delete old image
                $oldNews = $this->newsModel->getNewsById($id);
                if ($oldNews && !empty($oldNews['gambar_utama']) && file_exists(__DIR__ . '/../../../public/' . $oldNews['gambar_utama'])) {
                    unlink(__DIR__ . '/../../../public/' . $oldNews['gambar_utama']);
                }
            }
        }

        $this->newsModel->updateNews($id, $data);
        $_SESSION['flash_success'] = 'Berita berhasil diperbarui.';
        $this->redirect('/admin/news');
    }

    public function destroy($id)
    {
        // Get news to delete image
        $news = $this->newsModel->getNewsById($id);
        if ($news && !empty($news['gambar_utama']) && file_exists(__DIR__ . '/../../../public/' . $news['gambar_utama'])) {
            unlink(__DIR__ . '/../../../public/' . $news['gambar_utama']);
        }

        $this->newsModel->deleteNews($id);
        $_SESSION['flash_success'] = 'Berita berhasil dihapus.';
        $this->redirect('/admin/news');
    }
}
