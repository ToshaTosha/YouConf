<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Thesis extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status_id',
        'section_id',
        'co_authors'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->acceptsMimeTypes([
                'image/jpeg', // JPEG изображения
                'image/png',  // PNG изображения
                'application/pdf', // PDF файлы
                'application/msword', // DOC файлы
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX файлы
                'application/vnd.ms-excel', // XLS файлы
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // XLSX файлы
                'text/plain', // TXT файлы
                'application/rtf', // RTF файлы
                'application/vnd.ms-powerpoint', // PPT файлы
                'application/vnd.openxmlformats-officedocument.presentationml.presentation', // PPTX файлы
                'application/zip', // ZIP файлы (если нужно)
                'application/x-zip-compressed', // ZIP файлы (другой тип)
            ])
            ->useDisk('public');
    }

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

    public function chat()
    {
        return $this->morphOne(Chat::class, 'chatable');
    }

    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }
}
