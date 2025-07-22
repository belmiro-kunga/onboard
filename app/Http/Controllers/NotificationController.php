<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SimpleViewController;

class NotificationController extends SimpleViewController
{
    public function __construct()
    {
        parent::__construct('notification.index');
    }
}
