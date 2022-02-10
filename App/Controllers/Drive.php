<?php

namespace App\Controllers;

use App\Models\File;
use Core\Input;
use Core\View;

class Drive extends \Core\Controller
{
    public function countFiles() {
        $inputSession = new Input($_SESSION);
        $userId = $inputSession->int('userId');

        return [
            'files' => count(File::getByType($userId, 'FILE')),
            'shared' => 0,
            'favorites' => count(File::getByState($userId, 'FAVORITE')),
            'trash' => count(File::getByState($userId, 'DELETED')),
        ];
    }

    /*
     *
     */

    public function files()
    {
        session_start();

        $inputSession = new Input($_SESSION);
        $userId = $inputSession->int('userId');

        $inputParams = new Input($this->params);
        if ($inputParams->exists('id')) {
            $folderId = $inputParams->int('id');
;
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
        session_start();

        $inputSession = new Input($_SESSION);
        $userId = $inputSession->int('userId');

        $inputRequest = new Input($_FILES);
        $files = $inputRequest->get('files');
        $all_files = count($files['tmp_name']);

        $parent_id = null;
        $inputParams = new Input($this->params);
        if ($inputParams->exists('id')) {
            $parent_id = $inputParams->int('id');
        }

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

    public function filesDelete()
    {
        session_start();

        $input = new Input($_SESSION);
        $userId = $input->int('userId');

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
        session_start();

        $inputSession = new Input($_SESSION);
        $userId = $inputSession->int('userId');

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
            header('Content-Disposition: attachment; filename="'. $name .'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: '.$size);
            header("Content-Range: 0-".($size-1)."/".$size);

            readfile($path);
            exit;
        }

    }

    /*
     *
     */

    public function favorites()
    {
        session_start();

        $input = new Input($_SESSION);

        $userId = $input->int('userId');

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
        session_start();

        $input = new Input($_SESSION);

        $userId = $input->int('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        $file = File::get($fileId);
        File::updateState($fileId, 'FAVORITE');
    }

    public function favoritesDelete()
    {
        session_start();

        $input = new Input($_SESSION);

        $userId = $input->int('userId');

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
        session_start();

        $input = new Input($_SESSION);

        $userId = $input->int('userId');

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
        session_start();

        $input = new Input($_SESSION);

        $userId = $input->int('userId');

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
        session_start();

        $input = new Input($_SESSION);
        $userId = $input->int('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        $file = File::get($fileId);
        File::updateState($fileId, 'NONE');
    }

    public function trashDelete()
    {
        session_start();

        $input = new Input($_SESSION);
        $userId = $input->int('userId');

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
