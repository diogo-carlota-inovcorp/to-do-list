<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function clear()
    {
        auth()->user()->notifications()->delete();
        return back();
    }
}
