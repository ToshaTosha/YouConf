<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'application_id',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
