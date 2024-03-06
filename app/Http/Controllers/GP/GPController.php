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

        }
    }
}
