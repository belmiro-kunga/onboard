<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SimpleViewController;

class WelcomeController extends SimpleViewController
{
    public function __construct()
    {
        parent::__construct('welcome');
    }
}
