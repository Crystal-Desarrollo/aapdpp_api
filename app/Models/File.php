<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class File extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ["path", "name", "alt_text", 'original_name'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'fileable_type', 'fileable_id'];

    public function fileable()
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
        $originalName = $file->getClientOriginalName();

        $storedFile = File::create([
            'name' => $fileName,
            'path' => url('/storage/' . $filePath),
            'alt_text' =>  $altText || pathinfo($originalName, PATHINFO_FILENAME),
            'original_name' => $originalName
        ]);

        return $storedFile;
    }
}
