<?php

namespace App\Controllers;

use Core\View;

class Drive extends \Core\Controller
{
    public function files()
    {
        View::render('drive/files.php');
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
