<?php

namespace App\Controllers;

use App\Models\File;
use Core\Input;
use Core\Request;
use Core\Validation;
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

        $inputRequest = new Input();
        $inputParams = new Input($this->params);

        if ($inputRequest->exists('search')) {
            $search = $inputRequest->str('search');
            $files = File::search($search);
            $ancestors = null;
        } else if ($inputParams->exists('id')) {
            $folderId = $inputParams->int('id');;
            $files = File::getByParent($folderId);
            $ancestors = File::getAncestors($folderId);
        } else {
            $files = File::getRoot($userId);
            $ancestors = null;
        }

        View::render('drive/files.php', [
            'id' => 'files',
            'files' => $files,
            'folderId' => $folderId ?? null,
            'count' => $this->countFiles(),
            'ancestors' => $ancestors,
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

            try {
                File::createIfNotExists($userId, $folderName, 0, 'FOLDER', 'NONE', null, $parent_id);
            } catch(\PDOException $ex) {
                http_response_code(400);
                echo json_encode([
                    "errors" => "A file with the name of '$folderName' already exists."
                ]);

                return;
            }
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

    public function filesPut()
    {
        $_PUT = json_decode(file_get_contents('php://input'), true);

        $userId = Request::get('userId');

        $validation = new Validation($this->params);
        $fileId = $validation->name('id')->required()->int()->get();

        if (!$validation->isValid()) {
            http_response_code(400);
            echo json_encode([
                "errors" => $validation->getErrors()
            ]);

            return;
        }

        $validation = new Validation($_PUT);
        $moves = $validation->name('moves')->required()->get();

        if ($validation->isValid()) {
            foreach($moves as $moveId) {
                File::updateParent($moveId, $fileId == 0 ? null : $fileId);
            }

            return;
        }

        $fileName = $validation->name('fileName')->str()->required()->get();

        if (!$validation->isValid()) {
            http_response_code(400);
            echo json_encode([
                "errors" => $validation->getErrors()
            ]);

            return;
        }

        $file = File::get($fileId);
        $validation->assert($file !== null, "File not found");

        if (!$validation->isValid()) {
            http_response_code(400);
            echo json_encode([
                "errors" => $validation->getErrors()
            ]);

            return;
        }

        try {
            File::rename($fileId, $fileName);
        } catch (\PDOException $ex) {
            http_response_code(400);
            echo json_encode([
                "errors" => "A file with the name of '$fileName' already exists."
            ]);

            return;
        }
    }

    public function filesDelete()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        $file = File::get($fileId);
        File::propagateState($fileId, 'DELETED');

        echo json_encode([
            'count' => $this->countFiles(),
        ]);
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

        echo json_encode([
            'count' => $this->countFiles(),
        ]);
    }

    public function favoritesDelete()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        $file = File::get($fileId);
        File::updateState($fileId, 'NONE');

        echo json_encode([
            'count' => $this->countFiles(),
        ]);
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

        File::propagateState($fileId, 'NONE');

        echo json_encode([
            'count' => $this->countFiles(),
        ]);
    }

    public function trashDelete()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        $file = File::get($fileId);

        if ($file['type'] === 'FOLDER') {
            $children = File::getDescendants($fileId);

            foreach ($children as $child) {
                $path = '/data/' . $child['owner_id'] . '/' . $child['id'];

                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }

        File::delete($fileId);

        $path = '/data/' . $file['owner_id'] . '/' . $fileId;
        if (file_exists($path)) {
            unlink($path);
        }

        echo json_encode([
            'count' => $this->countFiles(),
        ]);
    }
}
