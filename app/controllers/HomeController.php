<?php

namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;

class HomeController
{
    private $templates, $queryBuilder;

    public function __construct(Engine $engine, QueryBuilder $queryBuilder)
    {
        $this->templates = $engine;
        $this->queryBuilder = $queryBuilder;
    }

    public function main()
    {
        $users = $this->queryBuilder->getAll('users', ['id', 'username', 'image', 'email', 'state', 'position', 'phone', 'address', 'vk', 'tg	', 'inst']);

        echo $this->templates->render('users', ['users' => $users]);
    }

    public function register()
    {
        echo $this->templates->render('register');
    }

    public function login()
    {
        echo $this->templates->render('login');
    }

    public function profile($id)
    {
        echo $this->templates->render('profile');
    }

    public function status($id)
    {
        $user = $this->queryBuilder->getOne('users', $id);

        // Нужно найти в массиве нужный элемент и поставить его первым, чтобы потом верно отрисовать в шаблоне
        $statuses = ['success' => 'Онлайн', 'warning' => 'Отошел', 'danger' => 'Не беспокоить'];
        $currentStatus = [$user['state'] => $statuses[$user['state']]];
        unset($statuses[$user['state']]);
        $statuses = $currentStatus + $statuses;

        echo $this->templates->render('status', ['user' => $user, 'statuses' => $statuses]);
    }

    public function user($id)
    {

        $user = $this->queryBuilder->getOne('users', $id);

        echo $this->templates->render('user', ['user' => $user]);
    }

    public function createUser($id)
    {
        echo $this->templates->render('create_user');
    }

    public function media($id)
    {
        echo $this->templates->render('media');
    }

    public function security($id)
    {
        echo $this->templates->render('security');
    }


}