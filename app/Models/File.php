<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    protected $fillable = ['path', 'name', 'fileable_id', 'fileable_type'];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
