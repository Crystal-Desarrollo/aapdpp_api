<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;
    protected $fillable = ["icon", "visible_text", "url"];

    /**
     * Get the link's icon
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value == "" ? "fas fa-globe"  : $value,
        );
    }

    /**
     * Get the link's visible text
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function visibleText(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $value == "" ? $attributes["url"] : $value,
        );
    }
}
