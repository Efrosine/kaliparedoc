<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\BaseNotificationsController;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends BaseNotificationsController
{
    /**
     * The view path for the notifications index page.
     * 
     * @var string
     */
    protected $indexView = 'client.notifications.index';

    /**
     * Display a listing of the user's notifications and mark them as read.
     */
    public function index()
    {
        // Mark all notifications as read when client views them
        NotificationService::markAllAsRead(Auth::id());

        return parent::index();
    }
}
