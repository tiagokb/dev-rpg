<?php

namespace App\Http\Controllers\Sheets;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class SheetsController extends Controller
{
    public function config()
    {
        return Inertia::render('Sheets/Config');
    }

    public function view()
    {
        return Inertia::render('Sheets/View');
    }
}
