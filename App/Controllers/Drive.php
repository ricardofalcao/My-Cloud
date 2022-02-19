<?php

namespace App\Controllers;

use App\Models\Access;
use App\Models\File;
use App\Models\User;
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
            'disk_usage' => File::getDiskUsage($userId),
        ];
    }

    /*
     *
     */

    private function _attachAccesses(&$files)
    {
        $toId = function ($value) {
            return $value['id'];
        };

        if (count($files) > 0) {
            $accesses = Access::getAll(array_map($toId, $files));
            foreach ($accesses as $access) {
                foreach ($files as &$file) {
                    if ($file['id'] == $access['file_id']) {
                        $file['accesses'][] = $access;
                        break;
                    }
                }
            }
        }
    }

    private function _getSorts()
    {
        $validation = new Validation();
        $q = $validation->name('q')->required()->get();

        if (!$validation->isValid()) {
            return [];
        }

        return $q;
    }

    /*
     *
     */

    public function files()
    {
        $userId = Request::get('userId');

        $sorts = $this->_getSorts();

        $inputRequest = new Input();
        $inputParams = new Input($this->params);

        if ($inputRequest->exists('search')) {
            $search = $inputRequest->str('search');
            $files = File::search($search, $sorts);
            $ancestors = null;
        } else if ($inputParams->exists('id')) {
            $folderId = $inputParams->int('id');;
            $files = File::getByParent($folderId, $sorts);
            $ancestors = File::getAncestors($folderId);
        } else {
            $files = File::getRoot($userId, $sorts);
            $ancestors = null;
        }

        $this->_attachAccesses($files);

        View::render('drive/files.php', [
            'id' => 'files',
            'files' => $files,
            'folderId' => $folderId ?? null,
            'count' => $this->countFiles(),
            'ancestors' => $ancestors,
            'sorts' => $sorts,
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
            } catch (\PDOException $ex) {
                http_response_code(400);
                echo json_encode([
                    "errors" => "A file with the name of '$folderName' already exists."
                ]);

                return;
            }
        } else {
            $files = $inputRequest->get('files');
            $all_files = count($files['tmp_name']);

            $dir = getcwd() . '/../data/' . $userId;
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
        $moves = $validation->name('moves')->get();

        if ($moves !== null) {
            $size = count($moves);
            foreach ($moves as $moveId) {
                try {

                    File::updateParent($moveId, $fileId == 0 ? null : $fileId);

                } catch (\PDOException $ex) {
                    if ($size === 1) {
                        http_response_code(400);
                        echo json_encode([
                            "errors" => 'Já existe um ficheiro com esse nome nessa pasta'
                        ]);

                        return;
                    }
                }
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
            $file = File::get($fileId);

            echo json_encode([
                'file' => $file
            ]);
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

        try {
            File::updateParent($fileId, null);
            File::propagateState($fileId, 'DELETED');

            $file = File::get($fileId);

            echo json_encode([
                'file' => $file,
                'count' => $this->countFiles(),
            ]);
        } catch (\PDOException $ex) {
            http_response_code(400);
            echo json_encode([
                "errors" => "Could not delete file."
            ]);

            return;
        }
    }

    /*
     *
     */

    public function shared()
    {
        $sorts = $this->_getSorts();

        $userId = Request::get('userId');
        // verificar root

        $files = Access::getRoot($userId, $sorts);

        $this->_attachAccesses($files);

        View::render('drive/files.php', [
            'id' => 'shared',
            'files' => $files,
            'count' => $this->countFiles(),
            'sorts' => $sorts,
        ]);
    }

    public function sharedPost()
    {
        $ownerId = Request::get('userId');
        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        $validation = new Validation();
        $username = $validation->name('username')->required()->str()->get();
        $type = $validation->name('type')->required()->str()->get();

        if ($validation->isValid()) {
            $user = User::getByUsername($username);
            $validation->assert($user !== null, 'Utilizador não encontrado.');

            if ($validation->isValid()) {
                $validation->assert($user['id'] !== $ownerId, 'Você já tem acesso a este ficheiro!');

                if ($validation->isValid()) {
                    try {
                        Access::create($user['id'], $fileId, $type);

                        $accesses = Access::getAll([$fileId]);
                        echo json_encode([
                            'accesses' => $accesses
                        ]);

                        return;
                    } catch (\PDOException $ex) {
                        http_response_code(400);

                        echo json_encode([
                            "errors" => 'Esse utilizador já tem acesso a esse ficheiro!'
                        ]);

                        return;
                    }
                }
            }
        }

        if (!$validation->isValid()) {
            http_response_code(400);

            echo json_encode([
                "errors" => $validation->getErrors()
            ]);

            return;
        }
    }

    public function sharedDelete()
    {
        $_DELETE = json_decode(file_get_contents('php://input'), true);

        $ownerId = Request::get('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        $validation = new Validation($_DELETE);
        $userId = $validation->name('userId')->required()->int()->get();

        if ($validation->isValid()) {
            try {
                Access::delete($userId, $fileId);

                $accesses = Access::getAll([$fileId]);
                echo json_encode([
                    'accesses' => $accesses
                ]);

                return;
            } catch (\PDOException $ex) {
                http_response_code(400);

                echo json_encode([
                    "errors" => 'Esse utilizador já tem acesso a esse ficheiro!'
                ]);

                return;
            }
        }

        if (!$validation->isValid()) {
            http_response_code(400);

            echo json_encode([
                "errors" => $validation->getErrors(),
                'request' => $_REQUEST,
                'delete' => $_DELETE
            ]);

            return;
        }
    }

    /*
     *
     */

    public function favorites()
    {
        $userId = Request::get('userId');
        // verificar root

        $sorts = $this->_getSorts();

        $files = File::getRootFavorites($userId);

        $this->_attachAccesses($files);

        View::render('drive/files.php', [
            'id' => 'favorites',
            'files' => $files,
            'count' => $this->countFiles(),
            'sorts' => $sorts
        ]);
    }

    public function favoritesPost()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        File::updateState($fileId, 'FAVORITE');
        $file = File::get($fileId);

        echo json_encode([
            'file' => $file,
            'count' => $this->countFiles(),
        ]);
    }

    public function favoritesDelete()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        File::updateState($fileId, 'NONE');
        $file = File::get($fileId);

        echo json_encode([
            'file' => $file,
            'count' => $this->countFiles(),
        ]);
    }

    /*
     *
     */

    public function trash()
    {
        $userId = Request::get('userId');
        // verificar root

        $sorts = $this->_getSorts();

        $files = File::getByState($userId, 'DELETED');

        $this->_attachAccesses($files);

        View::render('drive/files.php', [
            'id' => 'trash',
            'files' => $files,
            'count' => $this->countFiles(),
            'sorts' => $sorts
        ]);
    }

    public function trashPost()
    {
        $userId = Request::get('userId');

        $inputParams = new Input($this->params);
        $fileId = $inputParams->int('id');

        try {
            File::updateParent($fileId, null);
            File::propagateState($fileId, 'NONE');

            $file = File::get($fileId);

            echo json_encode([
                'file' => $file,
                'count' => $this->countFiles(),
            ]);
        } catch (\PDOException $ex) {
            http_response_code(400);
            echo json_encode([
                "errors" => "Could not restore file."
            ]);

            return;
        }
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
                $path = getcwd() . '/../data/' . $child['owner_id'] . '/' . $child['id'];

                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }

        File::delete($fileId);

        $path = getcwd() . '/../data/' . $file['owner_id'] . '/' . $fileId;
        if (file_exists($path)) {
            unlink($path);
        }

        echo json_encode([
            'count' => $this->countFiles(),
        ]);
    }
}
