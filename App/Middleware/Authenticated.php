<?php

namespace App\Middleware;

use App\Models\User;
use Core\Asset;
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
            header('Location: ' . Asset::path('/auth/login'), true, 303);
            return false;
        }

        $user = User::get($userId);
        $validation->assert($user !== null, "Invalid user");

        if (!$validation->isValid()) {
            header('Location: ' . Asset::path('/auth/login'), true, 303);
            return false;
        }

        Request::attr([
            'userId' => $userId,
            'user' => $user
        ]);

        return true;
    }

}