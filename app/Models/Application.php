<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;
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

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function versions()
    {
        return $this->hasMany(ApplicationVersion::class);
    }

    public function latestVersion()
    {
        return $this->versions()->latest()->first();
    }

    public function chat()
    {
        return $this->hasOneThrough(
            Chat::class,
            ApplicationVersion::class,
            'application_id', // Внешний ключ в ApplicationVersion
            'application_version_id', // Внешний ключ в Chat
            'id', // Локальный ключ в Application
            'id' // Локальный ключ в ApplicationVersion
        )->where('application_versions.is_current', true); // Опционально, если есть флаг текущей версии
    }
}
