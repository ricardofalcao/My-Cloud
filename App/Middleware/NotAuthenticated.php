<?php

namespace App\Middleware;

use Core\Input;
use Core\Middleware;
use Core\Request;
use Core\Validation;

class NotAuthenticated extends \Core\Middleware
{

    public function handle()
    {
        session_start();

        $validation = new Validation($_SESSION);
        $validation->name('userId')->int()->required();

        if ($validation->isValid()) {
            header('Location: /drive/files');
            return false;
        }

        return true;
    }

}