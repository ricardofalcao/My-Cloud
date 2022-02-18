<?php

namespace App\Controllers;

use App\Models\Access;
use App\Models\File;
use App\Models\User;
use Core\Input;
use Core\Request;
use Core\Validation;
use Core\View;

class DriveShare extends \Core\Controller
{
    public function countFiles()
    {
        $userId = Request::get('userId');

        $deleted = count(File::getByState($userId, 'DELETED'));

        return [
            'files' => count(File::getByType($userId, 'FILE')) - $deleted,
            'shared' => 0,
            'favorites' => count(File::getByState($userId, 'FAVORITE')),
            'trash' => $deleted,
            'disk_usage' => File::getDiskUsage($userId),
        ];
    }
}
