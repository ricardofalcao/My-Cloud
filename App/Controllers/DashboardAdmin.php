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

    public function statsApi()
    {
        $exec_loads = sys_getloadavg();
        $exec_cores = trim(shell_exec("grep -P '^processor' /proc/cpuinfo|wc -l"));
        $cpu = $exec_loads[1]/($exec_cores + 1) ;

        $exec_free = explode("\n", trim(shell_exec('free')));
        $get_mem = preg_split("/[\s]+/", $exec_free[1]);

        $dir = '/data';

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
                'total' => $get_mem[1],
                'used' => $get_mem[2],
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
