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

}