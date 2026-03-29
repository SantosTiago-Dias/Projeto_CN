<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    protected $fillable = [
        'createdby',
        'recivedby',
        'task_id',
        'title',
        'body',
        'type',
        'status',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function notifcationsNotread($query)
    {
        return $query->where('read', false);
    }

    public function markAsRead()
    {
        $this->update(['read' => true]);
    }

}
