<?php

namespace App\Services;

use App\Models\File;

class FileService
{

    public static function storeFile($file, $altText = null)
    {
        $name = \Str::random(90) . now()->format('U') . '.' . $file->extension();
        $filePath = $file->storeAs('/files', $name, 'public');

        $storedFile = File::create([
            'name' => $name,
            'path' => $filePath,
            'alt_text' =>  $altText ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
        ]);

        return $storedFile;
    }
}
