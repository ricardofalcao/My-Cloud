<?php

namespace App\Controllers;

use App\Models\User;
use Core\Request;
use Core\Validation;
use Core\View;

class DashboardUser extends \Core\Controller
{
    public function profile()
    {
        View::render('dashboard/user/profile.php');
    }

    public function profileName()
    {
        $userId = Request::get('userId');
        $validation = new Validation();

        $name = $validation->name('name')->str()->min(4)->required()->get();

        if (!$validation->isValid()) {
            View::render('dashboard/user/profile.php', [
                "errors" => $validation->getErrors(),
                "name" => $name,
            ]);

            return;
        }

        User::updateName($userId, $name);
        header('Location: /dashboard/user/profile');
    }

    public function profilePassword()
    {
        $userId = Request::get('userId');

        $validation = new Validation();
        $password = $validation->name('password')->str()->min(6)->required()->get();
        $confirmPassword = $validation->name('confirmPassword')->str()->min(6)->required()->get();

        $validation->assert($password === $confirmPassword, [
            'password' => 'Passwords must match.',
            'confirmPassword' => 'Passwords must match.'
        ]);

        if (!$validation->isValid()) {
            View::render('dashboard/user/profile.php', [
                "errors" => $validation->getErrors()
            ]);

            return;
        }

        User::updatePassword($userId, $password);
        header('Location: /dashboard/user/profile');
    }

    public function stats()
    {
        View::render('dashboard/user/stats.php');
    }
}
