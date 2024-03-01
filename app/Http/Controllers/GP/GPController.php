<?php

namespace App\Http\Controllers\GP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GPController extends Controller
{
    public function dashboard()
    {
        return view('GP.dashboard');
    }

    public function viewStatus()
    {
        return view('GP.pgharstatus');
    }
}
