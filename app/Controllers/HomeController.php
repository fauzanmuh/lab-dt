<?php

namespace App\Controllers;

use Core\Controller;
use Core\Pagination;
use App\Models\VisiMisi;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Publication;
use App\Models\Member;

/**
 * Home Controller
 */
class HomeController extends Controller
{
    protected $newsModel;
    protected $galleryModel;
    protected $publicationModel;
    protected $memberModel;

    public function __construct()
    {
        $this->newsModel = $this->loadModel(News::class);
        $this->galleryModel = $this->loadModel(Gallery::class);
        $this->publicationModel = $this->loadModel(Publication::class);
        $this->memberModel = $this->loadModel(Member::class);
    }

    /**
     * Display the home page
     */
    public function index()
    {
        $visiMisiModel = $this->loadModel(VisiMisi::class);
        $visi = $visiMisiModel->getVisi();
        $misi = $visiMisiModel->getMisi();

        // Use stored procedure to get sorted publications
        $recentPublications = $this->publicationModel->getSortedPublications(4);
        $mostCitedPublications = $this->publicationModel->getMostCitedPublications(3);
        $gallery = $this->galleryModel->getApprovedPhotos();
        $gallery = array_slice($gallery, 0, 6);

        // Fetch members for homepage
        $headOfLab = $this->memberModel->getMembersByRole('admin');
        $labMembers = $this->memberModel->getMembersByRole('operator');

        // Limit members if needed, e.g., take top 3
        $labMembers = array_slice($labMembers, 0, 3);

        return $this->view('home', [
            'title' => 'Welcome to Profile Lab DT',
            'message' => 'Welcome to Profile Lab DT',
            'visi' => $visi,
            'misi' => $misi,
            'recentPublications' => $recentPublications,
            'mostCitedPublications' => $mostCitedPublications,
            'gallery' => $gallery,
            'headOfLab' => $headOfLab,
            'labMembers' => $labMembers
        ]);
    }

    /**
     * Display about page
     */
    public function aboutPage()
    {
        return $this->view('about', [
            'title' => 'About Us - Profile Lab DT'
        ]);
    }

    public function FacilityPage()
    {
        return $this->view('facility', [
            'title' => 'Facility - Profile Lab DT'
        ]);
    }

    public function galleryPage()
    {
        $page = $_GET['page'] ?? 1;
        $limit = 9;
        $total = $this->galleryModel->countApprovedPhotos();
        $pagination = new Pagination($total, $limit, $page);

        $photos = $this->galleryModel->getPaginatedApprovedPhotos($limit, $pagination->getOffset());

        return $this->view('gallery', [
            'title' => 'Gallery - Profile Lab DT',
            'photos' => $photos,
            'pagination' => $pagination,
            'baseUrl' => '/gallery'
        ]);
    }

    public function publicationPage()
    {
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $total = $this->publicationModel->countApprovedPublications();
        $pagination = new Pagination($total, $limit, $page);

        $publications = $this->publicationModel->getPaginatedApprovedPublications($limit, $pagination->getOffset());

        return $this->view('publications', [
            'title' => 'Publication - Profile Lab DT',
            'publications' => $publications,
            'pagination' => $pagination,
            'baseUrl' => '/publications'
        ]);
    }

    public function NewsPage()
    {
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $total = $this->newsModel->countAllNews();
        $pagination = new Pagination($total, $limit, $page);

        $news = $this->newsModel->getApprovedNews($limit, $pagination->getOffset());
        $latest = $this->newsModel->getLatestNews();
        $others = $this->newsModel->getOtherNewsAfterLatest();

        return $this->view('news', [
            'title' => 'News - Profile Lab DT',
            'news' => $news,
            'latest' => $latest,
            'others' => $others,
            'pagination' => $pagination,
            'baseUrl' => '/news'
        ]);
    }


    public function loginPage()
    {
        return $this->view('login', [
            'title' => 'Login - Profile Lab DT'
        ]);
    }

    public function memberDetail($id)
    {
        $member = $this->memberModel->getMemberById($id);

        if (!$member) {
            // Handle 404 or redirect
            return $this->view('404', [
                'title' => 'Page Not Found - Profile Lab DT',
                'path' => "member/{$id}"
            ]);
        }

        $news = $this->newsModel->getApprovedNewsByAuthor($id);
        $publications = $this->publicationModel->getApprovedPublicationsByAuthor($id);
        $gallery = $this->galleryModel->getApprovedPhotosByUploader($id);

        return $this->view('member_detail', [
            'title' => $member['nama_lengkap'] . ' - Profile Lab DT',
            'member' => $member,
            'news' => $news,
            'publications' => $publications,
            'gallery' => $gallery
        ]);
    }

    public function notFound()
    {
        http_response_code(404);
        return $this->view('404', [
            'title' => 'Page Not Found - Profile Lab DT'
        ]);
    }
}