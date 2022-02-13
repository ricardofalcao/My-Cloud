<?php

namespace App\Controllers;

use App\Models\User;
use Core\AppException;
use Core\Input;
use Core\Validation;
use Core\View;

class Auth extends \Core\Controller
{
    public function login()
    {
        View::render('auth/login.php');
    }

    public function authenticate()
    {
        $validation = new Validation();

        $username = $validation->name('username')->str()->min(4)->required()->get();
        $password = $validation->name('password')->str()->min(6)->required()->get();

        if ($validation->isValid()) {
            $user = User::getByUsername($username);
            $validation->assert($user != false, [ "username" => "User not found" ]);

            if ($validation->isValid()) {
                $validation->assert(password_verify($password, $user['password_hash']), [
                    "username" => "",
                    "password" => "Invalid password",
                ]);
            }
        }

        if (!$validation->isValid()) {
            View::render('auth/login.php', [
                "errors" => $validation->getErrors(),
                "username" => $username
            ]);

            return;
        }

        $_SESSION['userId'] = $user['id'];
        header('Location: /drive/files');
    }

    public function logout()
    {
        session_start();
        session_destroy();

        header('Location: /auth/login');
    }

    /*
     *
     */


    public function register()
    {
        View::render('auth/register.php');
    }

    public function signup()
    {
        $validation = new Validation();

        $username = $validation->name('username')->str()->min(4)->required()->get();
        $name = $validation->name('name')->str()->min(4)->required()->get();
        $password = $validation->name('password')->str()->min(6)->required()->get();
        $confirmPassword = $validation->name('confirmPassword')->str()->min(6)->required()->get();

        $validation->assert($password === $confirmPassword, [
            'password' => 'Passwords must match.',
            'confirmPassword' => 'Passwords must match.'
        ]);

        if (!$validation->isValid()) {
            View::render('auth/register.php', [
                "errors" => $validation->getErrors(),
                "username" => $username,
                "name" => $name,
            ]);

            return;
        }

        $user = User::create($username, $name, $password);
        $_SESSION['userId'] = $user['id'];

        header('Location: /drive/files');
    }

    /*
     *
     */

    public function test()
    {
        $input = new Input($_SESSION);

        $userId = $input->int('userId');
        echo 'UserID: ' . $userId;
    }
}
