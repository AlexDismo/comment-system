<?php

namespace App\Services\File;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class FileHandler
{
    public function storeFile(UploadedFile $file): string
    {
        $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $filename = Str::uuid() . '.' . $extension;
        $path = $this->getDirectory() . $filename;
        $fullPath = storage_path('app/public/' . $path);

        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        rename($file->getPathname(), $fullPath);

        return $path;
    }

    abstract protected function getDirectory(): string;
}
