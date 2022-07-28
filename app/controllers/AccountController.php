<?php

namespace App\controllers;

use App\Image;
use App\QueryBuilder;
use App\Redirect;
use Delight\Auth\Auth;
use Delight\Auth\Role;
use Faker\Factory;

class AccountController
{
    private $auth, $redirect, $queryBuilder;

    public function __construct(Auth $auth, Redirect $redirect, QueryBuilder $queryBuilder)
    {
        $this->auth = $auth;
        $this->redirect = $redirect;
        $this->queryBuilder = $queryBuilder;
    }

    public function register($returnId = false)
    {
        try {
            $userId = $this->auth->register($_POST['email'], $_POST['password'], '', function ($selector, $token) {

                // Сделать отправку письма на почту

            });

            if ($userId && $returnId) {
                return $userId;
            }

            $this->redirect->success('Вы успешно зарегистрировались. Пройдите на почту для подтверждения аккаунта', '/');

        } catch (\Delight\Auth\InvalidEmailException $e) {
            $this->redirect->error('Неверный адрес электронной почты', '/register');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $this->redirect->error('Неверный пароль', '/register');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $this->redirect->error('Пользователь уже существует', '/register');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $this->redirect->error('Слишком много запрос, попробуйте позже', '/');
        }
    }

    public function login()
    {
        if ($_POST['remember'] == 'on') {
            $rememberDuration = (int)(60 * 60 * 24 * 365.25);
        } else {
            $rememberDuration = null;
        }

        try {
            $this->auth->login($_POST['email'], $_POST['password'], $rememberDuration);

            $this->redirect->success('Вы успешно авторизовались', '/');

        } catch (\Delight\Auth\InvalidEmailException $e) {
            $this->redirect->error('Неверный адрес электронной почты или пароль', '/login');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $this->redirect->error('Неверный адрес электронной почты или пароль', '/login');
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            $this->redirect->error('Адрес электронной почты не подтвержден', '/login');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $this->redirect->error('Слишком много запрос, попробуйте позже', '/login');
        }
    }

    public function logout()
    {
        $this->auth->logOut();
        $this->redirect->success('', '/');
    }

    public function addUser()
    {
        $dataPost = $_POST;

        $id = $this->register(true);

        if ($_FILES['avatar']) {
            $newAvatar = $this->prepareImage();
            $dataPost += $newAvatar;
        }

        $this->queryBuilder->update('users', $dataPost, $id);

        $this->redirect->success('Пользователь успешно создан', '/');
    }

    public function updateData($changeId)
    {
        $dataPost = $_POST;

        if ($this->weNotAdminAndChangeAlienData($changeId)) {
            $this->redirect->error('Вы не являетесь администратором', '/');
        } else {

            if ($_FILES['avatar']) {
                Image::delete($dataPost['oldAvatar']);
                $newAvatar = $this->prepareImage();
                $dataPost += $newAvatar;
                unset($dataPost['oldAvatar']);
            }

            $result = $this->queryBuilder->update('users', $dataPost, $changeId);

            if ($result) {
                $this->redirect->success('Данные успешно обновлены', '/');
            } else {
                $this->redirect->error('Ошибка обновления данных', '/');
            }

        }
    }

    /**
     * Проверяем на условие (Мы не админ и меняем чужие данные?)
     * @param $changeId
     * @return bool
     */
    private function weNotAdminAndChangeAlienData($changeId)
    {
        return (!$this->auth->hasRole(Role::ADMIN) && $changeId != $this->auth->getUserId());
    }

    /**
     * Подготавливает изображение, если какая то ошибка делает редирект с ошибкой,
     * иначе возвращает название изображения
     * @return array|array[]
     */
    private function prepareImage()
    {
        $avatarLabel = Image::upload($_FILES['avatar']);

        if (is_array($avatarLabel)) {
            $this->redirect->error($avatarLabel['message'], '/');
        }
        return ['avatar' => $avatarLabel];
    }
}