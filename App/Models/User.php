<?php

namespace App\Models;

use PDO;

class User extends \Core\Model
{
    public static function get($id)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM user WHERE id=?;");
        $stmt->execute([ $id ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll()
    {
        $db = static::db();
        $stmt = $db->query('SELECT * FROM user;');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     *
     */

    public static function create($username, $name, $password, $quota = null, $role = 'USER')
    {
        $password_hash = password_hash($password);

        $db = static::db();
        $stmt = $db->prepare("INSERT INTO user (username, name, password_hash, quota, role) VALUES (?, ?, ?, ?, ?);");
        $stmt->execute([ $username, $name, $password_hash, $quota, $role]);
    }

}