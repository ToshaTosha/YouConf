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

    public function theses()
    {
        return $this->hasMany(Thesis::class);
    }
}
