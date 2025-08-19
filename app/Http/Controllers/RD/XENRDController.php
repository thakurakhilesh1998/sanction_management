<?php

namespace App\Http\Controllers\RD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RDSanction;

class XENRDController extends Controller
{
    public function viewSanction()
    {
        try
        {
            $zone=Auth::user()->zone;
            $sanctionQuery=RDSanction::where('agency','xen');
             if($zone==='Shimla')
            {
                $sanctionQuery->where(function($query)
                {
                    $query->whereIn('district',['Shimla','Sirmaur','Solan','Kinnaur'])
                    ->orWhereIn('block',['Spiti','Anni','Nirmand']);
                });
            }
            else if($zone==='Dharamshala')
            {
                $sanctionQuery->where(function($query)
                {
                    $query->whereIn('district',['Kangra'])
                    ->orWhereIn('block',['Bharmour','Bhatiyat','Chamba','Mehla','Salooni','Tissa','Pangi']);
                });
            }
            else if($zone==='Mandi')
            {
                $sanctionQuery->where(function($query)
                {
                    $query->whereIn('district',['Mandi','Bilaspur'])
                    ->orWhereIn('block',['Banjar','Bhunter','Kullu','Naggar','Lahaul']);
                });
            }
             else if($zone==='Bangana')
            {
                $sanctionQuery->where(function($query)
                {
                    $query->whereIn('district',['Una','Hamirpur']);
                });
            }
            $sanction=$sanctionQuery->get();
            return view('XEN/RD/view',compact('sanction'));
        }

        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);;
        }
    }

    public function viewBlockWiseSan($district,$block,$work,$agency)
    {
        try
        {
            $work=urldecode($work);
            $rdSanction=RDSanction::where('district',$district)->where('block',$block)->where('work',$work)->where('agency','xen')->get();
            return view('XEN/RD/block-san',compact('rdSanction'));
        }   
        catch (Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
