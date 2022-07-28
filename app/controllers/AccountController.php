<?php

namespace App\controllers;

use App\Image;
use App\QueryBuilder;
use App\Redirect;
use App\User;
use Delight\Auth\Auth;
use Delight\Auth\Role;
use Faker\Factory;

class AccountController
{
    private $redirect, $user;

    public function __construct(Redirect $redirect, User $user)
    {
        $this->redirect = $redirect;
        $this->user = $user;
    }

    public function register()
    {
        $result = $this->user->register($_POST);

        $this->redirect->message($result[1], $result[0], $result[2]);

    }

    public function removeUser($id)
    {
        $result = $this->user->remove($id);
        $this->redirect->message($result[1], $result[0], '/');
    }

    public function login()
    {
        $result = $this->user->login($_POST);

        $this->redirect->message($result[1], $result[0], $result[2]);
    }

    public function logout()
    {
        $this->user->logOut();
        $this->redirect->success('', '/');
    }

    public function verificationEmail()
    {
        $result = $this->user->verificationEmail($_GET['selector'], $_GET['token']);
        $this->redirect->message($result[1], $result[0], $result[2]);
    }

    public function addUser()
    {

        $result = $this->user->register($_POST, true);

        if (!isset($result['id'])) $this->redirect->message($result[1], $result[0], $result[2]);

        $result = $this->user->updateDate($result['id'], $_POST, $_FILES);

        ($result === true ? $this->redirect->success('Пользователь успешно создан', '/') : $this->redirect->message($result[1], $result[0], $result[2]));

    }

    public function updateData($changeId)
    {
        $result = $this->user->updateDate($changeId, $_POST);

        ($result === true ? $this->redirect->success('Данные успешно обновлены', '/') : $this->redirect->message($result[1], $result[0], $result[2]));

    }

    public function updateStatus($changeId)
    {
        $result = $this->user->updateDate($changeId, $_POST);

        ($result === true ? $this->redirect->success('Статус успешно обновлен', '/') : $this->redirect->message($result[1], $result[0], $result[2]));

    }

    public function updateMedia($changeId)
    {
        $result = $this->user->updateDate($changeId, [], $_FILES);

        ($result === true ? $this->redirect->success('Изображение успешно обновлено', '/') : $this->redirect->message($result[1], $result[0], $result[2]));

    }

    public function updateSecurity($changeId)
    {
        $message = [];

        if ($_POST['newEmail'] !== $_POST['oldEmail']) {
            $message[] = $this->user->changeEmail($_POST['newEmail'], $changeId);
        }

        if ($_POST['password'] && $_POST['passwordConfirm']) {

            if ($_POST['password'] !== $_POST['passwordConfirm']) {
                $this->redirect->error('Пароли не совпадают', "/security/{$changeId}");
            }

            $message[] = $this->user->changePassword($_POST['password'], $changeId);
        }

        $this->redirect->arrFlash($message, '/');

    }

}