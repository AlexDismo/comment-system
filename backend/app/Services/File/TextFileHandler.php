<?php

namespace App\Services\File;

use App\Contracts\FileHandlerInterface;
use Exception;

class TextFileHandler extends FileHandler implements FileHandlerInterface
{
    /**
     * @throws Exception
     */
    public function validateFile($file): void
    {
        if ($file->getSize() > 100 * 1024) {
            throw new Exception('Text file size should not exceed 100KB.');
        }
    }

    protected function getDirectory(): string
    {
        return 'texts/';
    }
}
