<?php

namespace App\Controllers;

use App\Models\User;
use Core\Input;
use Core\View;

class Auth extends \Core\Controller
{
    public function login()
    {
        View::render('auth/login.php');
    }

    public function authenticate() {
        $input = new Input();

        $username = $input->str('username');
        $password = $input->str('password');

        $user = User::getByUsername($username);
        if ($user === false) {
            View::render('auth/login.php', [
                "errors" => [
                    "username" => "Invalid username"
                ],
                "username" => $username,
            ]);

            return;
        }

        if (!password_verify($password, $user['password_hash'])) {
            View::render('auth/login.php', [
                "errors" => [
                    "username" => "",
                    "password" => "Invalid password"
                ],
                "username" => $username,
            ]);

            return;
        }

        $_SESSION['userId'] = $user['id'];

        header('Location: /drive/files');
    }

    public function logout() {
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

    public function signup() {
        $input = new Input();

        $username = $input->str('username');
        $name = $input->str('name');
        $password = $input->str('password');
        $confirmPassword = $input->str('confirm-password');

        if ($password !== $confirmPassword) {
            View::render('auth/register.php', [
                'error' => 'Passwords dont match'
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
