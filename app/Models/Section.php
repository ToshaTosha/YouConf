<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'full_description',
        'start_date',
        'end_date',
    ];

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'section_user');
    }
}
