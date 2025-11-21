<?php

namespace App\Controllers;

use Core\Controller;
use Core\Http\Request;
use Core\Http\Response;

/**
 * Home Controller
 */
class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index(Request $request): Response
    {
        return $this->view('home', [
            'title' => 'Welcome to Profile Lab DT',
            'message' => 'Welcome to Profile Lab DT'
        ]);
    }

    /**
     * Display about page
     */
    public function about(Request $request): Response
    {
        return $this->view('about', [
            'title' => 'About Us'
        ]);
    }

    public function FacilityPage(Request $request): Response
    {
        return $this->view('facility', [
            'title' => 'Facility - Profile Lab DT'
        ]);
    }

    public function galleryPage(Request $request): Response
    {
        return $this->view('gallery', [
            'title' => 'Gallery - Profile Lab DT'
        ]);
    }

    public function publicationPage(Request $request): Response
    {
        return $this->view('publications', [
            'title' => 'Publication - Profile Lab DT'
        ]);
    }

    public function loginPage(Request $request): Response
    {
        return $this->view('login', [
            'title' => 'Login - Profile Lab DT'
        ]);
    }
}
