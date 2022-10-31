<?php

namespace Udacity\Apps\Web\Controllers\Resource;

trait FilesUploadsTrait {

    protected function getUploadedFile(string $fileKey): array {
        return !empty($_FILES[$fileKey]['name']) ? $_FILES[$fileKey] : [];
    }

    protected function uploadFile(string $fileKey, string $fileDest): void {
        move_uploaded_file($_FILES[$fileKey]['tmp_name'], $fileDest);
    }

}