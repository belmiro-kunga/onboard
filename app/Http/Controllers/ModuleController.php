<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SimpleViewController;

class ModuleController extends SimpleViewController
{
    public function __construct()
    {
        parent::__construct('module.index');
    }
}
