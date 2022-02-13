<?php

namespace App\Middleware;

use Core\Input;
use Core\Middleware;
use Core\Request;
use Core\Validation;

class Authenticated extends \Core\Middleware
{

    public function handle()
    {
        session_start();

        $validation = new Validation($_SESSION);
        $userId = $validation->name('userId')->int()->required()->get();

        if (!$validation->isValid()) {
            header('Location: /auth/login');
            return false;
        }

        Request::attr([
            'userId' => $userId
        ]);

        return true;
    }

}