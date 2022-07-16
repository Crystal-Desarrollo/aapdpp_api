<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];

    public function files()
    {
        return $this->morphMany(File::class, "fileable");
    }
}
