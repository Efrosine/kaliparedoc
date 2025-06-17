<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseNotificationsController;

class NotificationsController extends BaseNotificationsController
{
    /**
     * The view path for the notifications index page.
     * 
     * @var string
     */
    protected $indexView = 'admin.notifications.index';
}
