<?php

namespace App\Controllers;

use App\Models\User;
use Core\Asset;
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
            http_response_code(400);
            echo json_encode([
                "errors" => $validation->getErrors()
            ]);

            return;
        }

        User::updateName($userId, $name);
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
        header('Location: ' . Asset::path('/dashboard/user/profile'), true, 303);
    }

    public function stats()
    {
        View::render('dashboard/user/stats.php', [
            'count' => Drive::countFiles()
        ]);
    }
}
