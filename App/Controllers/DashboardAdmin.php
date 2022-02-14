<?php

namespace App\Controllers;

use App\Models\User;
use Core\Input;
use Core\View;

class DashboardAdmin extends \Core\Controller
{
    public function users()
    {
        View::render('dashboard/admin/users.php');
    }

    public function stats()
    {
        View::render('dashboard/admin/stats.php');
    }

    /*
     *
     */

    public function showUser() {
        $input = new Input($this->params);
        $userId = $input->int('id');

        $user = User::get($userId);
        echo $user;
    }

    public function listUsers() {
        $users = User::getAll();
        print_r($users);
    }

    public function createUser()
    {
        $input = new Input();

        $username = $input->str('username');
        $name = $input->str('name');
        $password = $input->str('password');

        User::create($username, $name, $password);
    }
}
