<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sanction;
class Home extends Controller
{
    public function index()
    {
        try
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
            $utilizedSan=Sanction::whereNotNull('uc')->sum('san_amount');
            $sanUtilized=$sanctionU->sum('san_amount');
            $newGP=Sanction::where('newGP','yes')->count();
            return view('FrontEnd/index',compact('totalSanction','sanUtilized','sanctionCount','utilizedSan','newGP'));
        }
        catch(\Exception $e)   
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    public function viewDetails($data=null)
    {
        try
        {
            if($data=='sanction')
            {
                $sanction=Sanction::with('progress')->orderBy('sanction_date','desc')->get();
                return view('FrontEnd.details',compact('sanction'));
            }
            elseif($data=='utilized')
            {
                $sanction=Sanction::whereHas('progress',function($query)
                {
                    $query->where('isFreeze','yes');
                })->orderBy('sanction_date','desc')->get();
                return view('FrontEnd.details',compact('sanction'));
            }
            elseif($data=='newGp')
            {
                $sanction=Sanction::where('newGP','yes')->orderBy('sanction_date','desc')->get();
                return view('FrontEnd.details',compact('sanction'));
            }
            else
            {
                $sanction=Sanction::with('progress')->orderBy('sanction_date','desc')->get();
                return view('FrontEnd.details',compact('sanction'));
            }
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function showGpDetails($gp)
    {
        try
        {
            if($gp!=null)
            {
                $gpDetails=Sanction::where('gp',$gp)->with('progress')->get();
                return view('FrontEnd.gpDetails',compact('gpDetails'));
            }
            else
            {
                return redirect()->back()->withErrors(['error' => 'Gram Panchayat Not found']);
            }
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
