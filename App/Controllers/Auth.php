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
            throw new \Exception('User not found', 404);
        }

        if (!password_verify($password, $user['password_hash'])) {
            throw new \Exception('Incorrent password', 401);
        }

        session_start();
        $_SESSION['userId'] = $user['id'];

        header('Location: /drive/files');
    }

    public function logout() {
        session_start();
        session_destroy();

        header('Location: /auth/login');
    }

    public function test()
    {
        $input = new Input($_SESSION);

        session_start();

        $userId = $input->int('userId');
        echo 'UserID: ' . $userId;
    }
}
