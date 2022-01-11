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
        $on = $_POST;
        Input::check(['username', 'password'], $on);

        $username = Input::str($on['username']);
        $password = Input::str($on['password']);

        $user = User::getByUsername($username);
        if ($user === false) {
            throw new \Exception('User not found', 404);
        }

        if (!password_verify($password, $user['password_hash'])) {
            throw new \Exception('Incorrent password', 401);
        }

        session_start();
        $_SESSION['userId'] = $user['id'];

        echo $user['id'];
    }

    public function logout() {
        session_start();
        session_destroy();

        echo 'Logout';
    }

    public function test()
    {
        session_start();

        Input::check(['userId'], $_SESSION);

        $userId = Input::int($_SESSION['userId']);
        echo 'UserID: ' . $userId;
    }
}
