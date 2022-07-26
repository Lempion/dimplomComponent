<?php

namespace App\controllers;

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

    public function register()
    {
        try {
//            $faker = Factory::create();
//            for ($i = 0; $i < 10; $i++) {
//                $id = $this->auth->register($faker->email, $faker->domainWord, '');
//
//                $this->queryBuilder->update('users', [
//                    'username' => $faker->name,
//                    'state' => $faker->randomElement(['success', 'warning', 'danger']),
//                    'position' => $faker->jobTitle . ', ' . $faker->company,
//                    'phone' => $faker->e164PhoneNumber,
//                    'address' => $faker->address,
//                    'vk' => $faker->domainWord,
//                    'tg' => $faker->domainWord,
//                    'inst' => $faker->domainWord], $id);
//            }
            $userId = $this->auth->register($_POST['email'], $_POST['password'], '', function ($selector, $token) {

                $this->redirect->success('Вы успешно зарегистрировались. Пройдите на почту для подтверждения аккаунта', '/');

            });
//            echo 'We have signed up a new user with the ID ' . $userId;
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

    public function logout()
    {
        $this->auth->logOut();
        $this->redirect->success('', '/');
    }

    public function updateData($changeId)
    {
        if ($this->weNotAdminAndChangeAlienData($changeId)) {
            $this->redirect->error('Вы не являетесь администратором', '/');
        } else {
            $result = $this->queryBuilder->update('users', $_POST, $changeId);

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


}