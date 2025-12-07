<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\Contact;


class InfoLabController extends Controller
{
    protected $contactModel;

    public function __construct()
    {
        $this->contactModel = $this->loadModel(Contact::class);
    }

    public function index()
    {
        $contact = $this->contactModel->getContactInfo();

        return $this->view('admin/info-lab/index', [
            'contact' => $contact,
            'pageTitle' => 'Informasi Kontak',
            'layout' => 'layouts/admin'
        ]);
    }

    public function update()
    {
        $data = $_POST;
        $this->contactModel->updateContactInfo($data);
        $this->redirect('/admin/info-lab?success=updated');
    }
}
