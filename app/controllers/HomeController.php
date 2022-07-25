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

}