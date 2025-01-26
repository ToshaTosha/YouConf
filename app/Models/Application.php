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

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // Отношение к модели User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Отношение к модели Status
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
