<?php

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Pagination;
use App\Models\Gallery;
use App\Models\Member;

class GalleryController extends Controller
{
    protected $galleryModel;
    protected $memberModel;

    public function __construct()
    {
        $this->galleryModel = $this->loadModel(Gallery::class);
        $this->memberModel = $this->loadModel(Member::class);
    }

    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $limit = 8;
        $total = $this->galleryModel->countAllPhotos();
        $pagination = new Pagination($total, $limit, $page);

        $photos = $this->galleryModel->getPaginatedPhotos($limit, $pagination->getOffset());
        $members = $this->memberModel->getAllMembers();

        return $this->view('admin/gallery/index', [
            'photos' => $photos,
            'members' => $members,
            'pagination' => $pagination,
            'baseUrl' => '/admin/gallery',
            'pageTitle' => 'Gallery Management',
            'layout' => 'layouts/admin'
        ]);
    }

    public function store()
    {
        $data = $_POST;

        // Handle File Upload
        if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../public/uploads/gallery/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES['file_path']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['file_path']['tmp_name'], $targetPath)) {
                $data['file_path'] = 'uploads/gallery/' . $fileName;
            }
        }

        // Restrict operators from setting status
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $data['status'] = 'pending';
        }

        $this->galleryModel->createPhoto($data);
        $this->redirect('/admin/gallery');
    }

    public function update($id)
    {
        $data = $_POST;

        // Handle File Upload
        if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../public/uploads/gallery/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES['file_path']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['file_path']['tmp_name'], $targetPath)) {
                // Delete old file if exists
                $oldPhoto = $this->galleryModel->getPhotoById($id);
                if ($oldPhoto && !empty($oldPhoto['file_path']) && file_exists(__DIR__ . '/../../../public/' . $oldPhoto['file_path'])) {
                    unlink(__DIR__ . '/../../../public/' . $oldPhoto['file_path']);
                }
                $data['file_path'] = 'uploads/gallery/' . $fileName;
            }
        }

        // Restrict operators from changing status, and reset to pending on edit
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $data['status'] = 'pending';
        }

        $this->galleryModel->updatePhoto($id, $data);
        $this->redirect('/admin/gallery');
    }

    public function destroy($id)
    {
        // Get photo to delete file
        $photo = $this->galleryModel->getPhotoById($id);
        if ($photo && file_exists(__DIR__ . '/../../../public/' . $photo['file_path'])) {
            unlink(__DIR__ . '/../../../public/' . $photo['file_path']);
        }

        $this->galleryModel->deletePhoto($id);
        $this->redirect('/admin/gallery');
    }
}
