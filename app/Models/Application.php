<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status_id',
        'section_id',
    ];
}
