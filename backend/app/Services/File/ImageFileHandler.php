<?php

namespace App\Services\File;

use App\Contracts\FileHandlerInterface;
use Exception;
use Intervention\Image\ImageManager;


class ImageFileHandler extends FileHandler implements FileHandlerInterface
{
    /**
     * @throws Exception
     */

    public function validateFile($file): void
    {
        list($width, $height) = getimagesize($file);
        if ($width > 320 || $height > 240) {
            $this->resizeImage($file);
        }
    }

    private function resizeImage($file): void
    {
        $manager = ImageManager::gd();
        $img = $manager->read($file);
        $img->resize(320, 240);
        $img->save();
    }
    protected function getDirectory(): string
    {
        return 'images/';
    }
}
