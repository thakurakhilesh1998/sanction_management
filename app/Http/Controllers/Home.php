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
        $totalSanction=Sanction::sum('san_amount');
        $sanctionCount=Sanction::count();
        $sanctionU=Sanction::whereHas('progress',function($query)
        {
            $query->where('isFreeze','yes');
        })->get();
        $utilizedSan=$sanctionU->count();
        $sanUtilized=$sanctionU->sum('san_amount');
        $newGP=Sanction::where('newGP','yes')->count();
        return view('FrontEnd/index',compact('totalSanction','sanUtilized','sanctionCount','utilizedSan','newGP'));
    }

    public function viewDetails($data=null)
    {
        if($data=='sanction')
        {
            $sanction=Sanction::with('progress')->get();
            return view('FrontEnd.details',compact('sanction'));
        }
        elseif($data=='utilized')
        {
            $sanction=Sanction::whereHas('progress',function($query)
            {
                $query->where('isFreeze','yes');
            })->get();
            return view('FrontEnd.details',compact('sanction'));
        }
        elseif($data=='newGp')
        {
            $sanction=Sanction::where('newGP','yes')->get();
            return view('FrontEnd.details',compact('sanction'));
        }
        else
        {
            $sanction=Sanction::with('progress')->get();
            return view('FrontEnd.details',compact('sanction'));
        }
    }

    public function showGpDetails($gp)
    {
        $gpDetails=Sanction::where('gp',$gp)->with('progress')->get();
        return view('FrontEnd.gpDetails',compact('gpDetails'));
    }
}
