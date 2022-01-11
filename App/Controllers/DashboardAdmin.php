<?php

namespace App\Controllers;

use App\Models\User;
use Core\Input;

class DashboardAdmin extends \Core\Controller
{
    public function index()
    {
        echo 'hello';
    }

    /*
     *
     */

    public function showUser() {
        Input::check(['id'], $this->params);
        $userId = Input::int($this->params['id']);

        $user = User::get($userId);
        echo $user;
    }

    public function listUsers() {
        $users = User::getAll();
        print_r($users);
    }

    public function createUser()
    {
        $on = $_POST;
        Input::check(['username', 'name', 'password'], $on);

        $username = Input::str($on['username']);
        $name = Input::str($on['name']);
        $password = Input::str($on['password']);

        User::create($username, $name, $password);
    }
}
