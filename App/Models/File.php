<?php

namespace App\Models;

use PDO;

class File extends \Core\Model
{

    public static function get($id)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM file WHERE id=?");
        $stmt->execute([ $id ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByParent($parentId)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM file WHERE parent_id=?");
        $stmt->execute([ $parentId ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $db = static::db();
        $stmt = $db->prepare("INSERT INTO file (owner_id, name, size, type, state, parent_id) VALUES (?, ?, ?, ?, ?, ?);");
        $stmt->execute([ $ownerId, $name, $size, $type, $state, $parentId]);
    }

    public static function delete($id)
    {
        $db = static::db();
        $stmt = $db->prepare("DELETE FROM file WHERE id=?");
        $stmt->execute([ $id ]);
    }

}