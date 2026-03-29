<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function kiosk(): View
    {
        return view('kiosk');
    }

    public function kitchen(): View
    {
        return view('kitchen');
    }

    public function analytics(): View
    {
        return view('analytics');
    }

    public function adminMenu(): View
    {
        return view('admin-menu');
    }
}
