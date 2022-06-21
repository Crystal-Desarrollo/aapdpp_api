<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Casts\Attribute;

class File extends Model
{
    use HasFactory;
    protected $fillable = ["path", "name"];

    public function Fileable()
    {
        return $this->morphTo();
    }

    /**
     * Get the file's path
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value != '' ? "/storage/files/" . $value : '/default_image.png',
        );
    }
}
