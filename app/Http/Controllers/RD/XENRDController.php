<?php

namespace App\Http\Controllers\RD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RDSanction;
use App\Models\ProgressRD;
use App\Http\Requests\Progress\RDProgressData;

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
            $rdSanction=RDSanction::where('district',$district)->where('block',$block)->where('work',$work)->where('agency','xen')->with('progress_rd')->get();
            return view('XEN/RD/block-san',compact('rdSanction'));
        }   
        catch (Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function addProgressRd($block,$district,$work)
    {
        try
        {
            if($block==null || $district==null || $work==null)
            {
               return redirect()->back()->withErrors(['error' => 'All fields are required to be entered']); 
            }
            else
            {
                $rdSanction=RDSanction::where('block',$block)->where('district',$district)->where('work',$work)->get();

                if(count($rdSanction)===0)
                {
                    return redirect()->back()->withErrors(['error' => 'No data found for the given ID']);
                }
                return view('XEN.RD.progress',compact('rdSanction'));    
            }
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function saveProgressRd(RDProgressData $data)
    {
        try
        {
            $currentDate=now();
            $formatDate=$currentDate->format('Y-m-d H:i:s');
            $validated=$data->validated();
            

            $progress=ProgressRD::where('work',$validated['work'])->first();
            if(!$progress)
            {
                $progress=new ProgressRD;
                $progress->completion_percentage=$validated['completion_percentage'];
                $progress->remarks=$validated['remarks'];
                $progress->block=$validated['block'];
                $progress->district=$validated['district'];
                $progress->p_update=$formatDate;
                $progress->work=$validated['work'];
                $progress->save();
                return redirect('xen/view-block-san'.'/'.$validated['district'].'/'.$validated['block'].'/'.$validated['work'].'/'.'xen')->with('message','Progress Added Successfully');  
            }
            else
            {
                return redirect('xen/view-block-san'.'/'.$validated['district'].'/'.$validated['block'].'/'.$validated['work'].'/'.'xen')->with('message','Progress Already Added');  
            }
        }
        catch (\Exception $e)
        {
            dd($e->getMessage());
        }
    }
}
