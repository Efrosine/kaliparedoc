<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

abstract class BaseNotificationsController extends Controller
{
    /**
     * The view path for the notifications index page.
     * 
     * @var string
     */
    protected $indexView;

    /**
     * Display a listing of the user's notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view($this->indexView, compact('notifications'));
    }

    /**
     * Mark a notification as read.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function markAsRead(Notification $notification)
    {
        // Check if notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $notification->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAllAsRead()
    {
        NotificationService::markAllAsRead(Auth::id());

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
