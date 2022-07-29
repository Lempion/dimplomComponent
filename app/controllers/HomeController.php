<?php

namespace App\controllers;

use App\QueryBuilder;
use App\Redirect;
use App\User;
use Delight\Auth\Role;
use Faker\Factory;
use League\Plates\Engine;

class HomeController
{
    private $templates, $queryBuilder, $user, $redirect;

    public function __construct(Engine $engine, QueryBuilder $queryBuilder, User $user, Redirect $redirect)
    {
        $this->templates = $engine;
        $this->user = $user;
        $this->queryBuilder = $queryBuilder;
        $this->redirect = $redirect;
    }

    public function main()
    {
        $users = $this->queryBuilder->getAll('users', ['id', 'username', 'avatar', 'email', 'state', 'position', 'phone', 'address', 'vk', 'tg	', 'inst']);

        echo $this->templates->render('users', ['users' => $users]);
    }

    public function register()
    {
        if ($this->user->isLoggedIn()) $this->redirect->success('', '/');

        echo $this->templates->render('register');
    }

    public function login()
    {
        if ($this->user->isLoggedIn()) $this->redirect->success('', '/');

        echo $this->templates->render('login');
    }

    public function profile($id)
    {
        $user = $this->queryBuilder->getOne('users', $id);

        echo $this->templates->render('profile', ['user' => $user]);
    }

    public function status($id)
    {
        if (!$this->user->hasRole(Role::ADMIN) && $id != $this->user->userId()) $this->redirect->success('', '/');

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
        if (!$this->user->hasRole(Role::ADMIN) && $id != $this->user->userId()) $this->redirect->success('', '/');

        $user = $this->queryBuilder->getOne('users', $id);

        echo $this->templates->render('user', ['user' => $user]);
    }

    public function createUser()
    {
        if (!$this->user->hasRole(Role::ADMIN)) $this->redirect->success('', '/');

        $faker = Factory::create();

        echo $this->templates->render('create_user', ['faker' => $faker]);
    }

    public function media($id)
    {
        if (!$this->user->hasRole(Role::ADMIN) && $id != $this->user->userId()) $this->redirect->success('', '/');

        $user = $this->queryBuilder->getOne('users', $id, ['id', 'avatar']);

        echo $this->templates->render('media', ['user' => $user]);
    }

    public function security($id)
    {
        if (!$this->user->hasRole(Role::ADMIN) && $id != $this->user->userId()) $this->redirect->success('', '/');

        $user = $this->queryBuilder->getOne('users', $id, ['id', 'email']);

        echo $this->templates->render('security', ['user' => $user]);
    }


}