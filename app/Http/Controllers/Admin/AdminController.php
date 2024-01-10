<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        return view('Admin/index');
    }

    public function dashboard()
    {
        try
        {      
            $user=Auth::user()->count();
            return view('Admin.Users.dashboard',compact('user'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' =>$e->getMessage()]);
        }
       
    }
}
