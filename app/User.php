<?php

namespace App;

use App\exceptions\CouldNotSendEmailException;
use App\exceptions\WeNotAdminAndChangeAlienDataException;
use Delight\Auth\Auth;
use Delight\Auth\Role;

class User
{
    private $auth, $queryBuilder, $mailer, $messageVerification;

    public function __construct(Auth $auth, QueryBuilder $queryBuilder, Mailer $mailer)
    {
        $this->auth = $auth;
        $this->queryBuilder = $queryBuilder;
        $this->mailer = $mailer;
    }

    public function register($data, $returnID = false)
    {
        try {
            $userId = $this->auth->register($data['email'], $data['password'], '', function ($selector, $token) {
                $this->messageVerification = "<p>Для подтверждения почты, пройдите по ссылке http://diplom.proj.ru/verification_email/?selector=$selector&token=$token</p>";
            });

            $this->mailer->send($data['email'], 'Верификация', $this->messageVerification);

            $message = ($returnID ? ['id' => $userId] : ['success', 'Вы успешно зарегистрировались, пройдите на почту для верификации', '/']);

        } catch (\Delight\Auth\InvalidEmailException $e) {
            $message = ['error', 'Неверный адрес электронной почты', '/register'];
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $message = ['error', 'Неверный пароль', '/register'];
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $message = ['error', 'Пользователь уже существует', '/register'];
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $message = ['error', 'Слишком много запрос, попробуйте позже', '/register'];
        } catch (CouldNotSendEmailException $e) {
            $message = ['error', 'Не удалось отправит сообщение на почту, обратитесь к администратору', '/'];
        }

        return $message;

    }

    public function remove($id)
    {
        try {
            $this->weNotAdminAndChangeAlienData($id);

            $this->auth->admin()->deleteUserById($id);
            $avatar = $this->queryBuilder->getOne('users', $id, ['avatar']);
            Image::delete($avatar['avatar']);
            return ['success', 'Пользователь удалён'];
        } catch (\Delight\Auth\UnknownIdException $e) {
            return ['error', 'Неизвестный ID'];
        } catch (WeNotAdminAndChangeAlienDataException $e) {
            return ['error' => 'Вы не являетесь администратором'];
        }
    }

    public function login($data)
    {
        if ($data['remember'] == 'on') {
            $rememberDuration = (int)(60 * 60 * 24 * 365.25);
        } else {
            $rememberDuration = null;
        }

        try {
            $this->auth->login($data['email'], $data['password'], $rememberDuration);

            $message = ['success', 'Вы успешно авторизовались', '/'];

        } catch (\Delight\Auth\InvalidEmailException $e) {
            $message = ['error', 'Неверный адрес электронной почты или пароль', '/login'];
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $message = ['error', 'Неверный адрес электронной почты или пароль', '/login'];
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            $message = ['error', 'Адрес электронной почты не подтвержден', '/login'];
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $message = ['error', 'Слишком много запрос, попробуйте позже', '/login'];
        }

        return $message;
    }

    public function logout()
    {
        $this->auth->logOut();
    }

    public function updateDate($id, $post = [], $files = [])
    {
        try {
            $this->weNotAdminAndChangeAlienData($id);

            if ($files['avatar']) {
                $oldAvatar = $this->queryBuilder->getOne('users', $id, ['avatar']);

                if ($oldAvatar) {
                    Image::delete($oldAvatar['avatar']);
                }

                $newAvatar = $this->prepareImage();
                if (!isset($newAvatar['avatar'])) {
                    return $newAvatar;
                } else {
                    $post += $newAvatar;
                }

            }

            $this->queryBuilder->update('users', $post, $id);
        } catch (WeNotAdminAndChangeAlienDataException $e) {
            return ['error', 'Вы не являетесь администратором', '/'];
        }


        return true;
    }

    public function changeEmail($newEmail, $changeId)
    {
        $oldId = $this->auth->getUserId();

        try {
            $this->weNotAdminAndChangeAlienData($changeId);

            if ($this->auth->hasRole(Role::ADMIN)) {
                $this->auth->admin()->logInAsUserById($changeId);
            }

            $this->auth->changeEmail($newEmail, function ($selector, $token) {
                $this->messageVerification = "<p>Для привязки этой почты, перейдите по ссылке http://diplom.proj.ru/verification_email/?selector=$selector&token=$token</p>";
            });

            if ($oldId !== $this->userId()) {
                $this->auth->admin()->logInAsUserById($oldId);
            }
            $this->mailer->send($newEmail, 'Смена почты', $this->messageVerification);

            $message = ['success' => 'Перейдите на указанную почту для верификации'];

        } catch (\Delight\Auth\UnknownIdException $e) {
            $message = ['error' => 'Неизвестный идентификатор'];
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            $message = ['error' => 'Адрес электронной почты не подтвержден'];
        } catch (\Delight\Auth\InvalidEmailException $e) {
            $message = ['error' => 'Неверный адрес электронной почты'];
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $message = ['error' => 'Адрес электронной почты уже существует'];
        } catch (\Delight\Auth\NotLoggedInException $e) {
            $message = ['error' => 'Не вошел'];
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $message = ['error' => 'Слишком много запросов'];
        } catch (CouldNotSendEmailException $e) {
            $message = ['error', 'Не удалось отправит сообщение на почту, обратитесь к администратору'];
        } catch (WeNotAdminAndChangeAlienDataException $e) {
            $message = ['error' => 'Вы не являетесь администратором'];
        }

        return $message;
    }

    public function verificationEmail($selector, $token)
    {
        try {
            $this->auth->confirmEmail($selector, $token);

            return ['success', 'Почта подтверждена', '/'];
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            return ['error', 'Неверный токен', '/'];
        } catch (\Delight\Auth\TokenExpiredException $e) {
            return ['error', 'Токен истек', '/'];
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            return ['error', 'Адрес электронной почты уже существует', '/'];
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            return ['error', 'Слишком много запросов', '/'];
        }
    }

    public function changePassword($newPassword, $changeId)
    {
        try {
            $this->weNotAdminAndChangeAlienData($changeId);

            $this->auth->admin()->changePasswordForUserById($changeId, $newPassword);

            $message = ['success' => 'Пароль успешно изменен.'];
        } catch (\Delight\Auth\UnknownIdException $e) {
            $message = ['error' => 'Неизвестный ID'];
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $message = ['error' => 'Неверный пароль'];
        } catch (WeNotAdminAndChangeAlienDataException $e) {
            $message = ['error' => 'Вы не являетесь администратором'];
        }

        return $message;
    }

    public function hasRole($role)
    {
        return ($this->auth->hasRole($role));
    }

    public function userId()
    {
        return $this->auth->getUserId();
    }

    public function isLoggedIn()
    {
        return $this->auth->isLoggedIn();
    }

    /**
     * Проверяем на условие (Мы не админ и меняем чужие данные?)
     * @param $changeId
     */
    private function weNotAdminAndChangeAlienData($changeId)
    {
        if (!$this->hasRole(Role::ADMIN) && $changeId != $this->userId()) {
            throw new WeNotAdminAndChangeAlienDataException();
        }
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
            return ['error', $avatarLabel['message'], '/'];
        }
        return ['avatar' => $avatarLabel];
    }

}