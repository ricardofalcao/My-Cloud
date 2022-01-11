<?php

namespace App\Models;

use PDO;

class File extends \Core\Model
{

    public static function get($id)
    {

    }

    public static function getByParent($parentId)
    {

    }
    // return true se tem acesso
    /*public static function hasAccess($userId, $fileId)
    {

    }*/

    /*
     *
     */

    /*public static function update($id)
    {

    }*/

    public static function create($ownerId, $name, $size, $type, $state = 'NONE', $parentId = null)

    {
        $db = static::
    }

    public static function delete($id)
    {

    }

}