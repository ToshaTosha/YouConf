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
        'application_id',
        'date',
        'start_time',
        'duration',
        'end_time',
        'location_id',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    protected static function booted()
    {
        static::saving(function ($schedule) {
            // Вычисляем время окончания
            $startTime = Carbon::createFromFormat('H:i', $schedule->start_time);
            $endTime = $startTime->copy()->addMinutes((int)$schedule->duration);

            // Проверяем, есть ли пересекающиеся расписания для этой секции
            $conflictingSchedules = Schedule::whereHas('application', function ($query) use ($schedule) {
                $query->where('section_id', $schedule->application->section_id); // Проверяем по section_id
            })
                ->where('date', $schedule->date)
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
