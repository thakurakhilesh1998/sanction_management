<?php

namespace App\Http\Controllers\RGSA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CSCSanction;
use App\Models\Progress_CSC;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Progress\CSCProgressData;

class XENRGSAController extends Controller
{
    public function viewSanction()
    {
        try
        {
            $zone=Auth::user()->zone;
            $sanctionQuery=CSCSanction::where('agency','xen')->whereNotNull('san_pdf');;
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
            return view('XEN/CSC/view',compact('sanction'));
        }

        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);;
        }
    }

    public function viewGPSanRGSA($district,$block,$gp,$work,$agency)
    {
        
        try
        {
            $work=urldecode($work);
            $cscSan=CSCSanction::where('district',$district)->where('gp',$gp)->where('block',$block)->where('work',$work)->where('agency',$agency)->with('progress_csc')->get();
            return view('XEN/CSC/gp-san-csc',compact('cscSan'));
        }   
        catch (Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function addProgressCSC($gp,$block,$district,$work)
    {
        try
        {
            if($gp==null || $block==null || $district==null || $work==null)
            {
               return redirect()->back()->withErrors(['error' => 'All fields are required to be entered']); 
            }
            else
            {
                $cscSanction=CSCSanction::where('gp',$gp)->where('block',$block)->where('district',$district)->where('work',$work)->get();

                if(count($cscSanction)===0)
                {
                    return redirect()->back()->withErrors(['error' => 'No data found for the given ID']);
                }
                return view('XEN.CSC.progress',compact('cscSanction'));    
            }
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function saveProgressCSC(CSCProgressData $data)
    {
        try
        {
            $currentDate=now();
            $formatDate=$currentDate->format('Y-m-d H:i:s');
            $validated=$data->validated();
            

            $progress=Progress_CSC::where('work',$validated['work'])->first();
            if(!$progress)
            {
                $progress=new Progress_CSC;
                $progress->completion_percentage=$validated['completion_percentage'];
                $progress->remarks=$validated['remarks'];
                $progress->block=$validated['block'];
                $progress->district=$validated['district'];
                $progress->gp=$validated['gp'];
                $progress->p_update=$formatDate;
                $progress->work=$validated['work'];
                $progress->save();
                return redirect('xen/view-gp-san-rgsa'.'/'.$validated['district'].'/'.$validated['block'].'/'.$validated['gp'].'/'.$validated['work'].'/'.'xen')->with('message','Progress Added Successfully');  
            }
            else if($progress->completion_percentage==='-1')
            {
                $progress->completion_percentage=$data['completion_percentage'];
                $progress->p_update=$formatDate;
                $progress->update();
                return redirect('xen/view-gp-san-rgsa'.'/'.$validated['district'].'/'.$validated['block'].'/'.$validated['gp'].'/'.$validated['work'].'/'.'xen')->with('message','Progress Updated Successfully');  
            }
            else
            {
                return redirect('xen/view-gp-san-rgsa'.'/'.$validated['district'].'/'.$validated['block'].'/'.$validated['gp'].'/'.$validated['work'].'/'.'xen')->with('message','Progress Already Added');  
            }
        }
        catch (\Exception $e)
        {
            dd($e->getMessage());
        }
    }
}
