<?php

namespace App;

use Tamtamchik\SimpleFlash\Flash;

class Redirect
{
    private $flash;

    public function __construct(Flash $flash)
    {
        $this->flash = $flash;
    }

    public function error($message, $path)
    {
        $this->flash->error($message);
        header("Location:{$path}");
        die();
    }

    public function success($message, $path)
    {
        $this->flash->success($message);
        header("Location:{$path}");
        die();
    }

    public function message($message, $type, $path)
    {
        $this->flash->$type($message);
        header("Location:{$path}");
        die();
    }

    public function arrFlash($arrMessage, $path)
    {
        foreach ($arrMessage as $key => $message) {
            $typeMessage = array_keys($message)[0];
            $TextMessage = array_values($message)[0];

            $this->flash->$typeMessage($TextMessage);
        }

        header("Location:{$path}");
        die();
    }

}