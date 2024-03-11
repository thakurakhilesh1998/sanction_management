<?php

namespace App\Http\Controllers\GP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PGharStatus\PGharStatusImg;
use Illuminate\Support\Facades\Auth;
use App\Models\Sanction;
use App\Models\Progress;
use App\Models\Gp_List;
use App\Models\Pghar_Image;
use App\Http\Requests\Progress\ProgressData;

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

    public function uploadImg(PGharStatusImg $data)
    {
        
        try
        {
            $validatedStatus=$data->validated();
            $district=Auth::user()->district;
            $block=Auth::user()->block_name;
            $gpName=Auth::user()->gp_name;
            $gp_id=Gp_List::where('district_name',$district)->where('block_name',$block)->where('gp_name',$gpName)->first();
            if($data->hasFile('p_image'))
            {
                $uploadedStatus=$data->file('p_image');
                foreach($uploadedStatus as $u)
                {
                    $filename=$gp_id->id.'_'.time().'_'.$u->getClientOriginalName();
                    $u->move('uploads/pghar_images',$filename);
                    $gharStatus=new Pghar_Image;
                    $gharStatus->image_path=$filename;
                    $gharStatus->gp_id=$gp_id->id;
                    $gharStatus->save();
                }
                return redirect()->back();   
            }
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);;
        }
        
    }

    public function viewSanction()
    {
        try
        {
            $gp_name=Auth::user()->gp_name;
            $block=Auth::user()->block_name;
            $district=Auth::user()->district;
            $sanction=Sanction::where('district',$district)->where('block',$block)->where('gp',$gp_name)->doesntHave('progress')->get();
            return view('GP.view-sanction',compact('sanction'));    
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);;
        }

    }

    public function addProgress($id)
    {
        try
        {
            if($id==null)
            {
                return redirect()->back()->withErrors(['error' => 'Id not be null']);
            }
            else
            {
                $gp_name=Auth::user()->gp_name;
                $block=Auth::user()->block_name;
                $district=Auth::user()->district;
                $sanction=Sanction::where('id',$id)->where('district',$district)->where('block',$block)->where('gp',$gp_name)->first();
                if($sanction->count===0)
                {
                    return redirect()->back()->withErrors(['error' => 'No data found for the given ID']);
                }
                return view('GP.progress',compact('sanction'));    

            }
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);;
        }
    }

    public function newProgress(ProgressData $data)
    {
        try
        {
            $currentDate=now();
            $formatDate=$currentDate->format('Y-m-d H:i:s');
            $progressValidated=$data->validated();
            $progress=new Progress;

            if($progressValidated['completion_percentage']==-1)
            {
                $progress->completion_percentage='Completed';
            }
            else
            {
                $progress->completion_percentage=$progressValidated['completion_percentage'];
            }
            $progress->p_isComplete=$progressValidated['p_isComplete'];
            if($data->hasFile('p_uc'))
            {
                $file=$data->file('p_uc');
                $filename=$data->sanction_id.time().'.'.$file->getClientOriginalExtension();
                $file->move('uploads/ucs/',$filename);
                $progress->p_uc=$filename;
            }
    
            $progress->remarks=$progressValidated['remarks'];
            $progress->sanction_id=$progressValidated['sanction_id'];
            $progress->p_update=$formatDate;
            $progress->save();
            $p_stored=Progress::where('sanction_id',$data->sanction_id)->get()->first();
            if($data->hasFile('p_image'))
            {
                $uploadedImage=$data->file('p_image');
                foreach($uploadedImage as $u)
                {
                    $filename=$progress->sanction_id.'_'.time().'_'.$u->getClientOriginalName();
                    $u->move('uploads/images/',$filename);
                    $progress->image()->create(['image_path'=>$filename,'progress_id'=>$p_stored->id]);
                }
            }
            return redirect(url('gp/update'))->with('message',"Progress Added Successfully!");
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);;
        }
    }

    public function update()
    {
        try
        {
            $district=Auth::user()->district;
            $block=Auth::user()->block_name;
            $gp=Auth::user()->gp_name;

            $sanction = Sanction::whereHas('progress', function ($query) use ($district, $block, $gp) {
                $query->where('district', $district)
                      ->where('block', $block)
                      ->where('gp', $gp);
            })->with('progress')->get();
            return view('GP.update-progress',compact('sanction'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
