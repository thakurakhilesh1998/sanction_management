<?php

namespace App\Http\Controllers\District;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sanction;
use App\Models\Progress;
use App\Http\Requests\Progress\ProgressData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Image;
class DistrictController extends Controller
{

    public function dashboard()
    {
        $district=Auth::user()->district;
        $sanctionCount=Sanction::where('district', $district)->count();
        $totalFundRecived=Sanction::where('district', $district)->sum('san_amount');
        $totalNewGP=Sanction::where('district', $district)->where('newGP','yes')->count();

        $progressRecord=Progress::where('isFreeze','yes');

        $sanctionId=$progressRecord->pluck('sanction_id');

        $sanctionData=Sanction::whereIn('id',$sanctionId)->where('district', $district)->get();
        $freezedSanction=$sanctionData->count();
        $totalUtilized=$sanctionData->sum('san_amount');
        $notReported = Sanction::where('district', $district)
        ->leftJoin('progress', 'sanction.id', '=', 'progress.sanction_id')
        ->whereNull('progress.sanction_id')
        ->count();

        return view('District.dashboard',compact('sanctionCount','totalFundRecived','totalNewGP','freezedSanction','totalUtilized','notReported'));
    }
    public function index()
    {

        $district=Auth::user()->district;
        $sanction=Sanction::where('district', $district)->doesntHave('progress')->get();
    
        // $sanction = Sanction::select('gp','block', DB::raw('SUM(san_amount) as total_sanction_amount'))
        //     ->where('district', $district)
        //     ->groupBy('gp','block')
        //     ->get();
        return view('District.index',compact('sanction'));
    }

    public function details($gp)
    {   
        $district=Auth::user()->district;
        $sanction=Sanction::where('gp',$gp)->where('district', $district)->get();
        return view('District.details',compact('sanction'));
    }

    public function progress($id)
    {
        $district=Auth::user()->district;
        $sanction=Sanction::where('id',$id)->first();
        return view('District.progress',compact('sanction'));
    }

    public function addProgress(ProgressData $data)
    {
        $currentDate=now();
        $formatDate=$currentDate->format('Y-m-d H:i:s');
        $progressValidated=$data->validated();
        $progress=new Progress;
        $progress->completion_percentage=$progressValidated['completion_percentage'];
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
        return redirect(url('district/update'))->with('message',"Progress Added Successfully!");
    }

    public function update()
    {
        $district=Auth::user()->district;

        $sanction = Sanction::whereHas('progress', function ($query) use ($district) {
            $query->where('district', $district);
        })->with('progress')->get();
        return view('district.update',compact('sanction'));
    }

    public function updateProgress($id)
    {
        $sanction=Sanction::find($id);
        $progress=$sanction->progress;
        // dd("Progress",$progress[0]->image);
        $images=$progress[0]->image;
        return view('district.update-form',compact('sanction','progress','images'));
    }
    
    public function Freeze(Request $req)
    {
        try
        {
            $progress=Progress::find($req->id);
            if(!$progress)
            {
                return response()->json(['error' => 'Progress not found'], 404);
            }
            $progress->isFreeze='yes';
            $progress->update();
            return response()->json(['success' => true, 'message' => 'Progress Frozen Successfully'], 200);
        }
        catch(\Exception $e)
        {
            return response()->json(['success' => false, 'error' => 'Error freezing progress', 'message' => $e->getMessage()], 500);
        } 
        
    }
   
    public function change(ProgressData $data,$id)
    {
        $currentDate=now();
        $formatDate=$currentDate->format('Y-m-d H:i:s');
        $progressValidated=$data->validated();
        $progress=Progress::find($id);
        // Progress Update Section
        if($progressValidated['p_isComplete']=="yes")
        {
            $progress->p_isComplete=$progressValidated['p_isComplete'];
            if(isset($progressValidated['p_uc']))
            {
                if($data->hasFile('p_uc'))
                {
                    $destination='uploads/ucs/'.$progress->p_uc;
                    if(File::exists($destination))
                    {
                        File::delete($destination);
                    }
                    $file=$data->file('p_uc');
                    $filename=$progressValidated['sanction_id'].time().'.'.$file->getClientOriginalExtension();
                    $file->move('uploads/ucs/',$filename);
                    $progress->p_uc=$filename;
                }
            }
        }
        else
        {
            $progress->p_isComplete=$progressValidated['p_isComplete'];
            $progress->completion_percentage=$progressValidated['completion_percentage'];
        }
        $progress->remarks=$progressValidated['remarks'];
        $progress->p_update=$formatDate;
        $progress->save();

        // Image Update Section
        if(isset($progressValidated['p_image']))
        {
            $images=Image::where('progress_id',$id)->get();

            foreach($images as $image)
            {
                $dest='uploads/images/'.$image->image_path;
                if(File::exists($dest))
                {
                    File::delete($dest);
                }
                Image::where('progress_id', $id)->delete();
            }
            if($data->hasFile('p_image'))
            {
                $uploadedImage=$data->file('p_image');
                foreach($uploadedImage as $u)
                {
                    $filename=$progress->sanction_id.'_'.time().'_'.$u->getClientOriginalName();
                    $u->move('uploads/images/',$filename);
                    $progress->image()->create(['image_path'=>$filename,'progress_id'=>$id]);
                }
            }
        }
        return redirect(url('district/update'))->with('message',"Progress Updated Successfully");

    }

    public function view($id)
    {
        $data = Sanction::with('progress.image')->find($id);
        return view('District.view',compact('data'));
    }

    public function allDetails()
    {
        $district=Auth::user()->district;
        $totalSanction=Sanction::where('district',$district)->sum('san_amount');
        $sanction=Sanction::where('district',$district)->with('progress')->orderBy('sanction_date')->get();
        return view('District.allsanction',compact('sanction','totalSanction'));
    }
}
