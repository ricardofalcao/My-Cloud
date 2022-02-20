<?php

namespace App\Controllers;

use Core\Validation;
use Core\View;

class Docs extends \Core\Controller
{
    public function index()
    {
        View::render('docs.php');
    }

    public function download()
    {
        $validation = new Validation();

        $zip = new \ZipArchive();
        $zipPath = getcwd() . '/../data/docs.zip';

        $validation->assert($zip->open($zipPath, \ZipArchive::CREATE) === TRUE, "Could not create zip file.");
        if (!$validation->isValid()) {
            goto error;
        }

        $rootPath = getcwd() . '/..';

        $directory = new \RecursiveDirectoryIterator($rootPath, \FilesystemIterator::FOLLOW_SYMLINKS);
        $filter = new MyRecursiveFilterIterator($directory);
        $files = new \RecursiveIteratorIterator($filter);

        foreach ($files as $name => $file) {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($name, strlen($rootPath) + 1);

            if (!$file->isDir()) {
                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            } else {
                if ($relativePath !== false)
                    $zip->addEmptyDir($relativePath);
            }
        }

        $zip->close();

        if (DriveDownload::_return_file('Documentation.zip', 'application/zip', $zipPath)) {
            unlink($zipPath);
        }

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

class MyRecursiveFilterIterator extends \RecursiveFilterIterator
{

    public function accept()
    {
        $name = $this->current()->getFilename();
        // Skip hidden files and directories.
        if ($name[0] === '.') {
            return FALSE;
        }

        if ($this->isDir()) {
            // Only recurse into intended subdirectories.
            return !in_array($name, ["data"]);
        }

        return true;
    }

}
