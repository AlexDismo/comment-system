<?php

namespace App\Enums;

enum FileType: string
{
    case Image = 'App\Services\File\ImageFileHandler';
    case Text = 'App\Services\File\TextFileHandler';

    public function getHandler(): string
    {
        return $this->value;
    }
}
