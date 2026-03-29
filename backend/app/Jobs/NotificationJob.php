<?php

namespace App\Jobs;

use App\Events\NotificationSent;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $createdby,
        public int $recivedby,
        public Task $task,
    )
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $notification = Notification::create([
            'createdby' => $this->createdby,
            'recivedby' => $this->recivedby,
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'description' => $this->task->description,
            'status' => $this->task->status,
        ]);

        broadcast(new NotificationSent($notification))->toOthers();
    }
}
