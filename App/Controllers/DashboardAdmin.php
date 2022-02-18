<?php

namespace App\Controllers;

use App\Models\File;
use App\Models\User;
use Core\Asset;
use Core\Input;
use Core\Request;
use Core\Validation;
use Core\View;

class DashboardAdmin extends \Core\Controller
{
    public function users()
    {
        $users = User::getAll();

        View::render('dashboard/admin/users.php', [
            'users' => $users
        ]);
    }

    public function usersPost()
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
            $users = User::getAll();

            View::render('dashboard/admin/users.php', [
                'users' => $users,
                "errors" => $validation->getErrors(),
                "username" => $username,
                "name" => $name,
            ]);

            return;
        }

        try {
            $user = User::create($username, $name, $password);
            header('Location: ' . Asset::path('/dashboard/admin/users'), true, 303);
        } catch(\PDOException $ex) {
            $users = User::getAll();

            View::render('dashboard/admin/users.php', [
                'users' => $users,
                "errors" => [
                    'username' => 'Utilizador já existe!'
                ],
                "username" => $username,
                "name" => $name,
            ]);

            return;
        }

    }

    public function usersDelete()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        $targetId = $inputParams->int('id');

        if ($userId === $targetId) {
            http_response_code(400);
            echo json_encode([
                'errors' => 'Não pode remover o seu próprio utilizador'
            ]);

            return;
        }

        User::delete($targetId);
        echo json_encode([
            "success" => true,
        ]);
    }

    /*
     *
     */

    public function stats()
    {
        View::render('dashboard/admin/stats.php');
    }

    public function statsApi()
    {
        $exec_loads = sys_getloadavg();
        $exec_cores = trim(shell_exec("grep -P '^processor' /proc/cpuinfo|wc -l"));
        $cpu = $exec_loads[1]/($exec_cores + 1) ;

        $exec_free = explode("\n", trim(shell_exec('free')));
        $get_mem = preg_split("/[\s]+/", $exec_free[1]);

        $dir = getcwd() . '/../data';

        // get disk space free (in bytes)
        $disk_free = disk_free_space($dir);

        // get disk space total (in bytes)
        $disk_total = disk_total_space($dir);

        echo json_encode([
            'disk' => [
                'total' => $disk_total,
                'used' => $disk_free,
            ],
            'cpu' => $cpu,
            'memory' => [
                'total' => $get_mem[1] * 1000,
                'used' => $get_mem[2] * 1000,
            ]
        ]);
    }

    /*
     *
     */

    public function showUser()
    {
        $input = new Input($this->params);
        $userId = $input->int('id');

        $user = User::get($userId);
        echo $user;
    }

    public function listUsers()
    {
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
