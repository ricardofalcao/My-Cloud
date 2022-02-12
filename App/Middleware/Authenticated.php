<?php

namespace App\Middleware;

use Core\Input;
use Core\Middleware;
use Core\Request;

class Authenticated extends \Core\Middleware
{

    public function handle()
    {
        session_start();
        $inputSession = new Input($_SESSION);

        try {
            $userId = $inputSession->int('userId');
            Request::attr([
                'userId' => $userId
            ]);

            return true;
        } catch(\Exception $e) {
            header('Location: /auth/login');
            return false;
        }
    }

}