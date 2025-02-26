<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationVersion extends Model
{
    use HasFactory;

    protected $fillable = ['application_id', 'title', 'description', 'status_id', 'version'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function chat()
    {
        return $this->morphMany(Chat::class, 'chatable');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
