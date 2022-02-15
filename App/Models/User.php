<?php

namespace App\Models;

use PDO;

class User extends \Core\Model
{
    public static function get($id)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM public.user WHERE id=?;");
        $stmt->execute([ $id ]);
        return $stmt->rowCount() == 0 ? null : $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByUsername($username)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM public.user WHERE username=?;");
        $stmt->execute([ $username ]);
        return $stmt->rowCount() == 0 ? null : $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll()
    {
        $db = static::db();
        $stmt = $db->query('SELECT * FROM public.user;');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     *
     */

    public static function create($username, $name, $password, $quota = null, $role = 'USER')
    {
        $password_hash = password_hash($password,  PASSWORD_DEFAULT);

        $db = static::db();
        $stmt = $db->prepare("INSERT INTO public.user (username, name, password_hash, quota, role) VALUES (?, ?, ?, ?, ?) RETURNING *;");
        $stmt->execute([ $username, $name, $password_hash, $quota, $role]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updatePassword($userId, $newPassword)
    {
        $password_hash = password_hash($newPassword,  PASSWORD_DEFAULT);

        $db = static::db();
        $stmt = $db->prepare("UPDATE public.user SET password_hash=? WHERE id=?;");
        $stmt->execute([ $password_hash, $userId ]);
    }

    public static function updateName($userId, $name)
    {
        $db = static::db();
        $stmt = $db->prepare("UPDATE public.user SET name=? WHERE id=?;");
        $stmt->execute([ $name, $userId ]);
    }

    public static function delete($userId)
    {
        $db = static::db();
        $stmt = $db->prepare("DELETE FROM public.user WHERE id=?;");
        $stmt->execute([ $userId ]);
    }

}