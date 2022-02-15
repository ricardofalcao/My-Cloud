<?php

namespace App\Middleware;

use App\Models\User;
use Core\Input;
use Core\Middleware;
use Core\Request;
use Core\Validation;

class IsAdmin extends \Core\Middleware
{

    public function handle()
    {
        $user = Request::get('user');
        return !empty($user) && $user['role'] === 'SUPERUSER';
    }

}