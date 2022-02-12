<?php

namespace App\Controllers;

use App\Models\File;
use Core\Input;
use Core\Request;
use Core\View;

class Drive extends \Core\Controller
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
        ];
    }

    /*
     *
     */

    public function files()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        if ($inputParams->exists('id')) {
            $folderId = $inputParams->int('id');;
            $files = File::getByParent($folderId);
        } else {
            $files = File::getRoot($userId);
        }

        View::render('drive/files.php', [
            'id' => 'files',
            'files' => $files,
            'folderId' => $folderId ?? null,
            'count' => $this->countFiles(),
        ]);
    }

    public function filesPost()
    {
        $userId = Request::get('userId');

        $parent_id = null;
        $inputParams = new Input($this->params);
        if ($inputParams->exists('id')) {
            $parent_id = $inputParams->int('id');
        }

        $inputRequest = new Input($_FILES);
        if ($inputRequest->exists('folderName')) {
            $folderName = $inputRequest->get('folderName');

            $id = File::create($userId, $folderName, 0, 'FOLDER', 'NONE', null, $parent_id);
        } else {
            $files = $inputRequest->get('files');
            $all_files = count($files['tmp_name']);

            $dir = '/data/' . $userId;
            if (!file_exists($dir)) {
                mkdir($dir, 0775, true);
            }

            for ($i = 0; $i < $all_files; $i++) {
                $size = $files['size'][$i];
                $name = $files['name'][$i];
                $tmp = $files['tmp_name'][$i];
                $type = $files['type'][$i];

                $id = File::create($userId, $name, $size, 'FILE', 'NONE', $type, $parent_id);
                $path = $dir . '/' . $id;

                move_uploaded_file($tmp, $path);
            }
        }
    }

    public function filesDelete()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        $file = File::get($fileId);
        File::updateState($fileId, 'DELETED');
    }

    /*
     *
     */

    public function download()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        $file = File::get($fileId);
        $path = '/data/' . $file['owner_id'] . '/' . $fileId;

        // verificar acesso
        if (file_exists($path)) {
            $mime_type = $file['mime_type'];
            $name = $file['name'];
            $size = $file['size'];

            header('Content-Description: File Transfer');
            header("Content-Type: $mime_type");
            header('Content-Disposition: attachment; filename="' . $name . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . $size);
            header("Content-Range: 0-" . ($size - 1) . "/" . $size);

            readfile($path);
            exit;
        }

    }

    /*
     *
     */

    public function favorites()
    {
        $userId = Request::get('userId');
        // verificar root

        $files = File::getRootByState($userId, 'FAVORITE');

        View::render('drive/files.php', [
            'id' => 'favorites',
            'files' => $files,
            'count' => $this->countFiles(),
        ]);
    }

    public function favoritesPost()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        $file = File::get($fileId);
        File::updateState($fileId, 'FAVORITE');
    }

    public function favoritesDelete()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        $file = File::get($fileId);
        File::updateState($fileId, 'NONE');
    }

    /*
     *
     */

    public function shared()
    {
        $userId = Request::get('userId');
        // verificar root

        //$files = File::getRoot($userId);
        $files = [];

        View::render('drive/files.php', [
            'id' => 'shared',
            'files' => $files,
            'count' => $this->countFiles(),
        ]);
    }

    public function trash()
    {
        $userId = Request::get('userId');
        // verificar root

        $files = File::getByState($userId, 'DELETED');

        View::render('drive/files.php', [
            'id' => 'trash',
            'files' => $files,
            'count' => $this->countFiles(),
        ]);
    }

    public function trashPost()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        $file = File::get($fileId);
        File::updateState($fileId, 'NONE');
    }

    public function trashDelete()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        $file = File::get($fileId);
        File::delete($fileId);

        $path = '/data/' . $file['owner_id'] . '/' . $fileId;
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
