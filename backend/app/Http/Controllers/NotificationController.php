<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationCollection;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index():NotificationCollection
    {

        $notifications = Notification::where('to', auth()->id())->notificationsNotRead()->orderBy('created_at', 'desc')->get();

        return new NotificationCollection($notifications);
    }

    public function markAsRead(Notification $notification):JsonResponse
    {
        if ($notification->to !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();
        return response()->json(null,204);
    }
}
