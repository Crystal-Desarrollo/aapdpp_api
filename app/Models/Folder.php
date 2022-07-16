<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function files()
    {
        return $this->morphMany(File::class, "fileable");
    }
}
