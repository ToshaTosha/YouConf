<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'section_id',
        'start_time',
        'duration',
        'end_time',
        'location_id',
        'event_type',
        'title',
        'description'
    ];

    protected $casts = [
        'start_time' => 'string',
        'end_time' => 'string',
    ];

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = is_string($value) ? $value : $value->format('H:i:s');
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = is_string($value) ? $value : $value->format('H:i:s');
    }

    public function isThesis(): bool
    {
        return $this->event_type === 'thesis';
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }


    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($schedule) {
            if ($schedule->thesis_id) {
                $thesis = Thesis::find($schedule->thesis_id);
                if ($thesis) {
                    $schedule->section_id = $thesis->section_id;
                }
            }
        });
        static::saving(function ($schedule) {
            // Вычисляем время окончания
            $startTime = Carbon::createFromFormat('H:i', $schedule->start_time);
            $endTime = $startTime->copy()->addMinutes((int)$schedule->duration);

            // Проверяем, есть ли пересекающиеся расписания для этой секции
            $conflictingSchedules = Schedule::whereHas('thesis', function ($query) use ($schedule) {
                $query->where('section_id', $schedule->thesis->section_id); // Проверяем по section_id
            })
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $endTime->format('H:i'))
                            ->whereRaw('ADDTIME(start_time, SEC_TO_TIME(duration * 60)) > ?', [$startTime->format('H:i')]);
                    });
                })
                ->where('id', '!=', $schedule->id) // Исключаем текущее расписание при обновлении
                ->exists();

            if ($conflictingSchedules) {
                throw ValidationException::withMessages([
                    'start_time' => 'Расписание пересекается с другим мероприятием в этой секции.',
                ]);
            }
        });
    }
}
