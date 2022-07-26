<?php

namespace App\controllers;

use App\Redirect;
use Delight\Auth\Auth;

class AccountController
{
    private $auth, $redirect;

    public function __construct(Auth $auth, Redirect $redirect)
    {
        $this->auth = $auth;
        $this->redirect = $redirect;
    }

    public function register()
    {
        try {
            $userId = $this->auth->register($_POST['email'], $_POST['password'], '', function ($selector, $token) {

                $this->redirect->success('Вы успешно зарегистрировались. Пройдите на почту для подтверждения аккаунта', '/');

            });

            echo 'We have signed up a new user with the ID ' . $userId;
        } catch (\Delight\Auth\InvalidEmailException $e) {
            $this->redirect->error('Неверный адрес электронной почты', '/register');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $this->redirect->error('Неверный пароль', '/register');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $this->redirect->error('Пользователь уже существует', '/register');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $this->redirect->error('Слишком много запрос, попробуйте позже', '/register');
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

}