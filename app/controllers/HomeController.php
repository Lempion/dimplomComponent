<?php

namespace App\controllers;

use League\Plates\Engine;

class HomeController
{
    private $templates;

    public function __construct()
    {
        $this->templates = new Engine('../app/views');
    }

    public function main()
    {
        echo $this->templates->render('users');
    }

    public function register()
    {
        echo $this->templates->render('register');
    }

    public function login()
    {
        echo $this->templates->render('login');
    }

    public function adminAddUser()
    {
        echo $this->templates->render('admin_create_user');
    }

    public function adminChangeStatus()
    {
        echo $this->templates->render('admin_change_status');
    }



}