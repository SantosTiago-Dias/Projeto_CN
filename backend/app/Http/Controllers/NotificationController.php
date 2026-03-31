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

        $notifications = Notification::where('to', auth()->id())->notificationsNotRead()->get();

        return new NotificationCollection($notifications);
    }

    public function markAsRead(Notification $notification):JsonResponse
    {
        $notification->markAsRead();
        return response()->json(null,204);
    }
}
