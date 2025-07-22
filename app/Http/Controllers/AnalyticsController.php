<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SimpleViewController;

class AnalyticsController extends SimpleViewController
{
    public function __construct()
    {
        parent::__construct('analytics.index');
    }
}
