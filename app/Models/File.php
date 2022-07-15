<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class File extends Model
{
    use HasFactory;
    protected $fillable = ["path", "name", "alt_text"];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'fileable_type', 'fileable_id'];

    public function Fileable()
    {
        return $this->morphTo();
    }

    private static function createFileName($file)
    {
        return Str::random(90) . now()->format('U') . '.' . $file->extension();
    }

    public static function storeFile($file, $altText = '')
    {
        $fileName = File::createFileName($file);
        $filePath = $file->storeAs('/files', $fileName, 'public');

        $storedFile = File::create([
            'name' => $fileName,
            'path' => url('/storage/' . $filePath),
            'alt_text' =>  $altText ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
        ]);

        return $storedFile;
    }
}
