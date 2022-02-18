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
            SELECT access.*, public.user.name FROM access 
                INNER JOIN public.user ON public.user.id = access.user_id
            WHERE file_id in ($qMarks)
"       );
        $stmt->execute($fileIds);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRoot($userId)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM public.access INNER JOIN public.file_ancestors on public.access.file_id=public.file_ancestors.id WHERE public.access.user_id=? AND state <> 'DELETED' ORDER BY file_ancestors.type DESC, file_ancestors.name");
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
        $stmt = $db->prepare("INSERT INTO public.access (user_id, file_id, type) VALUES (?, ?, ?);");
        $stmt->execute([$userId, $fileId, $type]);
    }

    public static function delete($userId, $fileId)
    {
        $db = static::db();
        $stmt = $db->prepare("DELETE FROM public.access WHERE user_id=? AND file_id=?;");
        $stmt->execute([$userId, $fileId]);
    }

}