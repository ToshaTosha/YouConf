<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'date',
        'start_time',
        'duration',
        'end_time',
        'location',
    ];

    // Связь с заявкой
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
