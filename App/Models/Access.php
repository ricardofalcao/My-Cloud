<?php

namespace App\Models;

use PDO;

class Access extends \Core\Model
{

    public static function getAll($fileIds)
    {
        $qMarks = str_repeat('?,', count($fileIds) - 1) . '?';

        $db = static::db();
        $stmt = $db->prepare("
            SELECT access.*, U1.name FROM access 
                INNER JOIN sie212239.user as U1 ON U1.id = access.user_id
            WHERE file_id in ($qMarks)
");
        $stmt->execute($fileIds);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllByUser($userId)
    {
        $db = static::db();
        $stmt = $db->prepare("
            SELECT access.* FROM access 
                INNER JOIN file_ancestors AS F1 ON F1.id = access.file_id
                INNER JOIN file AS F2 ON F2.id=ANY(F1.ancestors)
            WHERE access.user_id = ?
");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRoot($userId, $sorts = [])
    {
        $sortsQ = File::compileSorts(array_merge(['Dtype'], $sorts, ['Aname']));

        $db = static::db();
        $stmt = $db->prepare("SELECT F1.* FROM file_ancestors as F1
            INNER JOIN access as A1 on A1.file_id=F1.id
            LEFT JOIN access as A2 ON A2.file_id=F1.parent_id
            WHERE A1.user_id = ?
              AND F1.state <> 'DELETED'
              AND A2.file_id IS NULL $sortsQ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     *
     */

    /*public static function update($id)
    {

    }*/

    public static function create($userId, $fileId, $type)
    {
        $db = static::db();
        $stmt = $db->prepare("INSERT INTO access (user_id, file_id, type) VALUES (?, ?, ?);");
        $stmt->execute([$userId, $fileId, $type]);
    }

    public static function delete($userId, $fileId)
    {
        $db = static::db();
        $stmt = $db->prepare("DELETE FROM access WHERE user_id=? AND file_id=?;");
        $stmt->execute([$userId, $fileId]);
    }

}