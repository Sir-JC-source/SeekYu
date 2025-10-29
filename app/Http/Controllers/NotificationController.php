<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications->count()
        ]);
    }
}
