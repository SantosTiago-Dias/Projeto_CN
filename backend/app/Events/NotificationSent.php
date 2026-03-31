<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    /**
     * Create a new event instance.
     */
    public function __construct(public Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): Channel
    {
        // canal privado por utilizador — só o destinatário recebe
        return new PrivateChannel('user.' . $this->notification->to);
    }

    public function broadcastWith(): array
    {
        return [
            'id'    => $this->notification->id,
            'title' => $this->notification->title,
            'task_id' => $this->notification->task->id,
            'status'  => $this->notification->status,
            'from' => $this->notification->from
        ];
    }
}
