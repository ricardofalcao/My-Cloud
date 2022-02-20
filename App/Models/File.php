<?php

namespace App\Models;

use PDO;

class File extends \Core\Model
{

    public static function compileSorts($sorts) {
        if (count($sorts) > 0) {
            $query = "ORDER BY ";

            $sorts = array_unique($sorts);

            foreach ($sorts as $index => $sort) {
                if ($index > 0) {
                    $query .= ', ';
                }

                if ($sort[0] === 'A') {
                    $query .= substr($sort, 1, strlen($sort) - 1) . ' ASC';
                } else {
                    $query .= substr($sort, 1, strlen($sort) - 1) . ' DESC';
                }
            }

            return $query;
        }

        return "";
    }

    public static function search($userId, $text, $sorts = [])
    {
        $sortsQ = self::compileSorts($sorts);

        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM file WHERE owner_id = ? AND type='FILE' AND search_tokens @@ plainto_tsquery('portuguese', ?) $sortsQ");
        $stmt->execute([$userId, $text]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get($id)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM file_ancestors WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->rowCount() == 0 ? null : $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAncestors($id)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT F2.* FROM file_ancestors as F1 
        JOIN file_ancestors as F2 ON (F2.id = ANY(F1.ancestors) OR F2.id=F1.id)
        WHERE F1.id=?
        ORDER BY F2.ancestors");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getDescendants($id)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM file_ancestors WHERE ? = ANY(ancestors)");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function batchGet($ids)
    {
        $db = static::db();
        $qMarks = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $db->prepare("SELECT * FROM file WHERE id IN ($qMarks)");
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByParent($parentId, $sorts = [])
    {
        $sortsQ = self::compileSorts(array_merge(['Dtype'], $sorts,['Aname']));

        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM file_ancestors WHERE parent_id=? $sortsQ");
        $stmt->execute([$parentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRoot($userId, $sorts = [])
    {
        $sortsQ = self::compileSorts(array_merge(['Dtype'], $sorts,['Aname']));

        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM file_ancestors WHERE owner_id=? AND state <> 'DELETED' AND parent_id is NULL $sortsQ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRootFavorites($userId)
    {
        $db = static::db();

        $stmt = $db->prepare("
            SELECT F1.* FROM file as F1 
                LEFT JOIN file as F2 ON F1.parent_id=F2.id 
            WHERE F1.owner_id=? AND F1.state = 'FAVORITE' AND (F1.parent_id is NULL OR F2.state <> 'FAVORITE') 
            ORDER BY F1.type DESC, F1.name
        ");

        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByState($userId, $state)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM file WHERE owner_id=? AND state = ? ORDER BY type DESC, name");
        $stmt->execute([$userId, $state]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByType($userId, $type)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM file WHERE owner_id=? AND type = ? ORDER BY type DESC, name");
        $stmt->execute([$userId, $type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getDiskUsage($userId)
    {
        $db = static::db();
        $stmt = $db->prepare("SELECT SUM(size) as total FROM file WHERE owner_id=?");
        $stmt->execute([$userId]);
        return $stmt->rowCount() == 0 ? 0 : $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /*
     *
     */

    /*public static function update($id)
    {

    }*/

    public static function create($ownerId, $name, $size, $type, $state = 'NONE', $mime_type = null, $parentId = null)
    {
        $path_parts = pathinfo($name);
        $filename = $path_parts['filename'];

        $db = static::db();
        $stmt = $db->prepare("INSERT INTO file (owner_id, name, size, type, state, mime_type, parent_id, search_tokens) VALUES (?, ?, ?, ?, ?, ?, ?, to_tsvector('portuguese', ?)) ON CONFLICT (owner_id, name, coalesce(parent_id, '-1')) DO UPDATE SET size = excluded.size, type = excluded.type, state = excluded.state, mime_type = excluded.mime_type, search_tokens = excluded.search_tokens, modified_at=CURRENT_TIMESTAMP RETURNING id;");
        $stmt->execute([$ownerId, $name, $size, $type, $state, $mime_type, $parentId, $filename]);
        return $stmt->fetchColumn();
    }

    public static function createIfNotExists($ownerId, $name, $size, $type, $state = 'NONE', $mime_type = null, $parentId = null)
    {
        $path_parts = pathinfo($name);
        $filename = $path_parts['filename'];

        $db = static::db();
        $stmt = $db->prepare("INSERT INTO file (owner_id, name, size, type, state, mime_type, parent_id, search_tokens) VALUES (?, ?, ?, ?, ?, ?, ?, to_tsvector('portuguese', ?)) RETURNING id;");
        $stmt->execute([$ownerId, $name, $size, $type, $state, $mime_type, $parentId, $filename]);
        return $stmt->fetchColumn();
    }

    public static function rename($id, $newName)
    {
        $path_parts = pathinfo($newName);
        $filename = $path_parts['filename'];

        $db = static::db();
        $stmt = $db->prepare("UPDATE file SET name=?, search_tokens=to_tsvector('portuguese', ?), modified_at=CURRENT_TIMESTAMP WHERE id=?");
        $stmt->execute([$newName, $filename, $id]);
    }

    public static function updateState($id, $state)
    {
        $db = static::db();
        $stmt = $db->prepare("UPDATE file SET state=?, modified_at=CURRENT_TIMESTAMP WHERE id=?");
        $stmt->execute([$state, $id]);
    }

    public static function updateParent($id, $parentId)
    {
        $db = static::db();
        $stmt = $db->prepare("UPDATE file SET parent_id=?, modified_at=CURRENT_TIMESTAMP WHERE id=?");
        $stmt->execute([$parentId, $id]);
    }

    public static function propagateState($id, $state)
    {
        $db = static::db();
        $stmt = $db->prepare("UPDATE file SET state=?, modified_at=CURRENT_TIMESTAMP FROM file_ancestors WHERE file_ancestors.id=file.id AND (file.id=? OR ? = ANY(file_ancestors.ancestors))");
        $stmt->execute([$state, $id, $id]);
    }

    public static function delete($id)
    {
        $db = static::db();
        $stmt = $db->prepare("DELETE FROM file WHERE id=?");
        $stmt->execute([$id]);
    }

}