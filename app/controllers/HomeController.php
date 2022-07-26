<?php

namespace App\controllers;

use League\Plates\Engine;

class HomeController
{
    private $templates;

    public function __construct(Engine $engine)
    {
        $this->templates = $engine;
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

    public function profile()
    {
        echo $this->templates->render('profile');
    }

    public function status($vars = null)
    {
        echo $this->templates->render('status');
    }

    public function user($vars = null)
    {
        echo $this->templates->render('user');
    }

    public function createUser()
    {
        echo $this->templates->render('create_user');
    }

    public function media()
    {
        echo $this->templates->render('media');
    }

    public function security()
    {
        echo $this->templates->render('security');
    }


}