<?php

namespace App\Models;

use PDO;

class File extends \Core\Model
{

    public static function get($id)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM public.file_ancestors WHERE id=?");
        $stmt->execute([ $id ]);
        return $stmt->rowCount() == 0 ? null : $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAncestors($id)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT F2.* FROM public.file_ancestors as F1 
        JOIN public.file_ancestors as F2 ON (F2.id = ANY(F1.ancestors) OR F2.id=F1.id)
        WHERE F1.id=?
        ORDER BY F2.ancestors");
        $stmt->execute([ $id ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function batchGet($ids)
    {
        $db = static::db();
        $qMarks = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $db->prepare("SELECT * FROM public.file WHERE id IN ($qMarks)");
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByParent($parentId)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM public.file_ancestors WHERE parent_id=? ORDER BY type DESC, name");
        $stmt->execute([ $parentId ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRoot($userId)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM public.file_ancestors WHERE owner_id=? AND state <> 'DELETED' AND parent_id is NULL ORDER BY type DESC, name");
        $stmt->execute([ $userId ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRootByState($userId, $state)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM public.file WHERE owner_id=? AND state = ? AND parent_id is NULL ORDER BY type DESC, name");
        $stmt->execute([ $userId, $state ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByState($userId, $state)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM public.file WHERE owner_id=? AND state = ? ORDER BY type DESC, name");
        $stmt->execute([ $userId, $state ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByType($userId, $type)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM public.file WHERE owner_id=? AND type = ? ORDER BY type DESC, name");
        $stmt->execute([ $userId, $type ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     *
     */

    /*public static function update($id)
    {

    }*/

    public static function create($ownerId, $name, $size, $type, $state = 'NONE', $mime_type = null, $parentId = null)
    {
        $db = static::db();
        $stmt = $db->prepare("INSERT INTO public.file (owner_id, name, size, type, state, mime_type, parent_id) VALUES (?, ?, ?, ?, ?, ?, ?) ON CONFLICT (owner_id, name, coalesce(parent_id, '-1')) DO UPDATE SET size = excluded.size, type = excluded.type, state = excluded.state, mime_type = excluded.mime_type RETURNING id;");
        $stmt->execute([ $ownerId, $name, $size, $type, $state, $mime_type, $parentId]);
        return $stmt->fetchColumn();
    }

    public static function updateState($id, $state)
    {
        $db = static::db();
        $stmt = $db->prepare("UPDATE public.file SET state=? WHERE id=?");
        $stmt->execute([ $state, $id ]);
    }

    public static function delete($id)
    {
        $db = static::db();
        $stmt = $db->prepare("DELETE FROM public.file WHERE id=?");
        $stmt->execute([ $id ]);
    }

}