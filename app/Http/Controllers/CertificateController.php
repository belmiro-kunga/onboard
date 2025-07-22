<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SimpleViewController;

class CertificateController extends SimpleViewController
{
    public function __construct()
    {
        parent::__construct('certificate.index');
    }
}
