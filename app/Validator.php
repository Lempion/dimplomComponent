<?php

namespace App;

use SimpleValidator\Validator as Validate;

class Validator
{
    private static $errorsMessage = [
        'email.required' => 'Введите почту',
        'password.required' => 'Введите пароль',
        'password.min_length' => 'Слишком короткий пароль',
        'password.max_length' => 'Слишком длинный пароль',
        'email' => 'Почта введена не корректно',
    ];

    public static function validate($post, $rules)
    {
        $v = Validate::validate($post, $rules);

        $v->customErrors(self::$errorsMessage);

        if ($v->isSuccess() == true) {
            return true;
        } else {
            foreach ($v->getErrors() as $key => $message) {
                $prepareError[] = ['error' => $message];
            }

            return $prepareError;
        }

    }

}