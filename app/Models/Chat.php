<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'application_version_id',
    ];

    public function applicationVersion()
    {
        return $this->belongsTo(ApplicationVersion::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
