<?php

namespace App\Http\Controllers\File;

use App\Enums\FileType;
use App\Http\Controllers\Controller;
use App\Exceptions\UnsupportedFileTypeException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\File;

class FileController extends Controller
{
    protected $handler;

    /**
     * @throws UnsupportedFileTypeException
     */
    private function findHandler($file): void
    {
        $extension = $file->getClientOriginalExtension();
        $fileType = match ($extension) {
            'txt' => FileType::Text,
            'jpg', 'png', 'gif' => FileType::Image,
            default => throw new UnsupportedFileTypeException("Handler for file type {$extension} is not defined"),
        };

        $this->handler = app($fileType->getHandler());
    }

    /**
     * @throws UnsupportedFileTypeException
     */
    public function upload($file, $commentId) : string
    {
        $this->findHandler($file);
        $this->handler->validateFile($file);
        $path = $this->handler->storeFile($file);
        return \App\Models\File::create(
            [
                'path' => $path,
                'comment_id' => $commentId,
            ]);
    }
}
