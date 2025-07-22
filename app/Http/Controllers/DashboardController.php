<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SimpleViewController;

class DashboardController extends SimpleViewController
{
    public function __construct()
    {
        parent::__construct('dashboard.index');
    }
}
