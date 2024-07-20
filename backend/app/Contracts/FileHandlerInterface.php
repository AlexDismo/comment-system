<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface FileHandlerInterface
{
    public function validateFile(UploadedFile $file);
    public function storeFile(UploadedFile $file);
}
