<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Performance extends Model
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

    public function chat()
    {
        return $this->morphOne(Chat::class, 'chatable');
    }

    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }
}
