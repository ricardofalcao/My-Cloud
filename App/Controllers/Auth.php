<?php

namespace App\Controllers;

use Core\View;

class Auth extends \Core\Controller
{
    public function login()
    {
        View::render('auth/login.php');
    }
}
