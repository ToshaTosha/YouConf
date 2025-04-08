<?php

namespace App\Notifications;

use App\Models\Performance;
use App\Models\Status;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PerformanceStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Performance $performance,
        public Status $oldStatus,
        public Status $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Статус заявки изменён',
            'message' => sprintf(
                'Статус вашей заявки "%s" изменён с "%s" на "%s"',
                $this->performance->title,
                $this->oldStatus->name,
                $this->newStatus->name
            ),
            'performance_id' => $this->performance->id,
            'old_status' => $this->oldStatus->name,
            'new_status' => $this->newStatus->name,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Статус заявки изменён',
            'message' => sprintf(
                'Статус вашей заявки "%s" изменён с "%s" на "%s"',
                $this->performance->title,
                $this->oldStatus->name,
                $this->newStatus->name
            ),
            'performance_id' => $this->performance->id,
            'old_status' => $this->oldStatus->name,
            'new_status' => $this->newStatus->name,
        ];
    }
}
