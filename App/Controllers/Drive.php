<?php

namespace App\Controllers;

use App\Models\File;
use Core\Input;
use Core\View;

class Drive extends \Core\Controller
{
    public function files()
    {
        session_start();

        Input::check(['userId'], $_SESSION);
        $userId = Input::int($_SESSION['userId']);

        // verificar root

        $files = File::getRootFolder($userId);

        View::render('drive/files.php', [
            'files' => $files
        ]);
    }

    public function favorites()
    {
        View::render('drive/favorites.php');
    }

    public function shared()
    {
        View::render('drive/shared.php');
    }
    
    public function trash()
    {
        View::render('drive/trash.php');
    }
}
