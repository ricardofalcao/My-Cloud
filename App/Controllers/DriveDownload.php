<?php

namespace App\Controllers;

use App\Models\File;
use Core\Input;
use Core\Request;
use Core\Validation;
use Core\View;

class DriveDownload extends \Core\Controller
{

    private function _get_file_path($file)
    {
        return '/data/' . $file['owner_id'] . '/' . $file['id'];
    }

    private function _return_file($name, $mime_type, $filePath) {
        $size = filesize($filePath);

        header('Content-Description: File Transfer');
        header("Content-Type: $mime_type");
        header('Content-Disposition: attachment; filename="' . $name . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $size);
        header("Content-Range: 0-" . ($size - 1) . "/" . $size);

        readfile($filePath);
    }

    private function _zip_file($zip, $file, $root = '') {
        if ($file['type'] === 'FOLDER') {
            $files = File::getByParent($file['id']);

            foreach ($files as $childFile) {
                if ($childFile['type'] == 'FOLDER') {
                    $zip->addEmptyDir($root . $childFile['name']);
                    $this->_zip_file($zip, $childFile, $root . $childFile['name'] . '/');
                    continue;
                }

                $this->_zip_file($zip, $childFile, $root);
            }

            return true;
        }


        $path = $this->_get_file_path($file);
        if (!file_exists($path)) {
            return false;
        }

        $zip->addFile($path, $root . $file['name']);
        return true;
    }

    public function download()
    {
        $userId = Request::get('userId');

        $validation = new Validation($this->params);
        $files = $validation->name('files')->required()->get();

        if (!$validation->isValid()) {
            goto error;
        }

        if (count($files) > 1) {
            $zip = new \ZipArchive();
            $zipPath = '/data/' . $userId . '/folder_' . uniqid() . '.zip';

            $validation->assert($zip->open($zipPath, \ZipArchive::CREATE) === TRUE, "Could not create zip file.");

            if (!$validation->isValid()) {
                goto error;
            }

            $cloudFiles = File::batchGet($files);

            foreach ($cloudFiles as $cloudFile) {
                $this->_zip_file($zip, $cloudFile, $cloudFile['type'] === 'FOLDER' ? $cloudFile['name'] . '/' : '');
            }

            $zip->close();

            $this->_return_file('Folder.zip', 'application/zip', $zipPath);
            unlink($zipPath);
            return;
        }

        $fileId = $files[0];
        $file = File::get($fileId);

        $validation->assert($file !== null, "Could not find file entry.");

        if (!$validation->isValid()) {
            goto error;
        }

        if ($file['type'] === 'FOLDER') {
            $zip = new \ZipArchive();
            $zipPath = '/data/' . $userId . '/folder_' . uniqid() . '.zip';

            $validation->assert($zip->open($zipPath, \ZipArchive::CREATE) === TRUE, "Could not create zip file.");

            if (!$validation->isValid()) {
                goto error;
            }

            $this->_zip_file($zip, $file);
            $zip->close();

            $this->_return_file($file['name'] . '.zip', 'application/zip', $zipPath);
            unlink($zipPath);
            return;
        }

        $path = $this->_get_file_path($file);

        $validation->assert(file_exists($path), "Could not find file.");

        if (!$validation->isValid()) {
            goto error;
        }

        $this->_return_file($file['name'], $file['mime_type'], $path);
        return;

        error:
        {
            http_response_code(400);

            echo json_encode([
                "errors" => $validation->getErrors()
            ]);
        }
    }
}
