<?php

namespace App\Middleware;

use Core\Asset;
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
            header('Location: ' . Asset::path('/drive/files'), true, 303);
            return false;
        }

        return true;
    }

}