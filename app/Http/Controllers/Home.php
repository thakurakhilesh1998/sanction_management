<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sanction;
class Home extends Controller
{
    public function index()
    {
        $user=Auth::user();
        if($user)
        {
            return redirect($user->role);
        }
        return view('FrontEnd/index');
    }

    public function viewDetails($data=null)
    {
        dd('Data is',$data  );
        $sanction=Sanction::with('progress')->get();
        return view('FrontEnd.details',compact('sanction'));
    }
}
