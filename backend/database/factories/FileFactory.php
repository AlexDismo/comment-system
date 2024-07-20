<?php

namespace Database\Factories;

use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition()
    {
        $fileTypes = ['jpg', 'gif', 'png', 'txt'];
        $fileType = $this->faker->randomElement($fileTypes);

        $fileName = Str::random(10) . '.' . $fileType;

        if ($fileType === 'txt') {
            $content = $this->faker->text(50000);
            file_put_contents(storage_path('app/public/texts/' . $fileName), $content);
            $path = 'texts/' . $fileName;
        } else {
            $filePath = $this->faker->image(storage_path('app/public/images'), 320, 240, null, false);
            $path = 'images/' . $filePath;
        }

        return [
            'path' => $path,
            'comment_id' => \App\Models\Comment::factory(),
        ];
    }
}
