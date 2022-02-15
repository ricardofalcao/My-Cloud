<?php

namespace App\Controllers;

use Core\View;

class DashboardUser extends \Core\Controller
{
    public function profile()
    {
        View::render('dashboard/user/profile.php');
    }

    public function stats()
    {
        View::render('dashboard/user/stats.php');
    }
}
