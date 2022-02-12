<?php

namespace App\Middleware;

use Core\Input;
use Core\Middleware;

class NotAuthenticated extends \Core\Middleware
{

    public function handle()
    {
        session_start();
        $inputSession = new Input($_SESSION);

        if ($inputSession->exists('userId')) {
            header('Location: /drive/files');
            return false;
        }

        return true;
    }

}