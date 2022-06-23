<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;
    protected $fillable = ["body", "description", "title"];

    public function cover()
    {
        return $this->morphOne(File::class, "fileable");
    }

    public function description(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $value != '' ? $value : Str::of($attributes["body"])->limit(253)
        );
    }
}
