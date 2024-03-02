<?php
namespace App\Http\Controllers\District;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sanction;
use App\Http\Requests\Sanction\sanRequest;
use App\Models\Progress;
use App\Http\Requests\Progress\ProgressData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Image;
use Illuminate\Support\Facades\Hash;

class DistrictController extends Controller
{

    public function dashboard()
    {
        try
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
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
  
    }
    public function index()
    {
        try
        {
            $district=Auth::user()->district;
            $sanction=Sanction::where('district', $district)->doesntHave('progress')->get();
            return view('District.index',compact('sanction'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function details($gp)
    {   
        try
        {
            if($gp!=null)
            {   
                $district=Auth::user()->district;
                $sanction=Sanction::where('gp',$gp)->where('district', $district)->get();
                if($sanction->count===0)
                {
                    return redirect()->back()->withErrors(['error' => 'No data found for the given GP']);
                }
                return view('District.details',compact('sanction'));
            }   
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function progress($id)
    {
        try
        {
            if($id==null)
            {
                return redirect()->back()->withErrors(['error' => 'Id not be null']);
            }
            else
            {
                $district=Auth::user()->district;
                $sanction=Sanction::where('id',$id)->where('district',$district)->first();
                if($sanction->count()===0)
                {
                    return redirect()->back()->withErrors(['error' => 'No data found for the given ID']);
                }
    
                return view('District.progress',compact('sanction'));    
            }
          
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function addProgress(ProgressData $data)
    {
        try
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
             $sanction = Sanction::whereHas('progress', function ($query) use ($district) {
            $query->where('district', $district);
            })->with('progress')->get();
            return view('district.update',compact('sanction'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function updateProgress($id)
    {
        try
        {
            if($id!=null)
            {
                $district=Auth::user()->district;
                $sanction=Sanction::where('district',$district)->find($id);
            if($sanction->count()===0)
            {
                return back()->withErrors(['error' =>'No sanction found with this id']);
            }
                $progress=$sanction->progress;
                $images=$progress[0]->image;
                return view('district.update-form',compact('sanction','progress','images'));
            }
            else
            {
                return redirect()->back()->withErrors(['error' => 'ID not be null']);
            }
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
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
        try
        {
            if($id==null)
            {
                return redirect()->back()->withErrors(['error' =>'ID not be null']);
            }
            $currentDate=now();
            $formatDate=$currentDate->format('Y-m-d H:i:s');
            $progressValidated=$data->validated();
            $progress=Progress::find($id);
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
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    public function view($id)
    {
        try
        {
            if($id==null)
            {
                return redirect()->back()->withErrors(['error' =>'ID not be null']);
            }
            $data = Sanction::with('progress.image')->find($id);
            return view('District.view',compact('data'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function allDetails($data=null)
    {
        try
        {
            $district=Auth::user()->district;
            if($data==null)
            {
                $totalSanction=Sanction::where('district',$district)->sum('san_amount');
                $sanction=Sanction::where('district',$district)->with('progress')->orderBy('sanction_date')->get();
                return view('District.allsanction',compact('sanction','totalSanction'));
            }
            if($data=='freeze')
            {
                $sanction = Sanction::where('district',$district)->with(['progress' => function ($query) {
                    $query->where('isFreeze', 'yes');
                }])->whereHas('progress', function ($query) {
                    $query->where('isFreeze', 'yes');
                })->get();
                $totalSanction=$sanction->sum('san_amount');
                return view('District.allsanction',compact('sanction','totalSanction'));
            }
            elseif($data=='newGP')
            {
                $sanction=Sanction::where('district',$district)->where('newGP','yes')->get();
                $totalSanction=$sanction->sum('san_amount');
                return view('District.allsanction',compact('sanction','totalSanction'));
            }
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
       
    }
    public function changePassword()
    {
        return view('District.changepassword');
    }

    public function updatePassword(Request $req)
    {
        try
        {
            $req->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8',
                'new_password_confirmation' => 'required|same:new_password',
            ]);
            $user = auth()->user();
            if (Hash::check($req->current_password, $user->password)) {
                $user->update([
                    'password' => bcrypt($req->new_password),
                ]);

                return redirect(url('district/change-password'))->with('message', 'Password changed successfully.');
            }

            return redirect()->back()->withErrors(['current_password' => 'The provided current password is incorrect.']);
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function addSanction()
    {
        try
        {
            $district=Auth::user()->district;
            return view('District.addSanction',compact('district'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function saveSanction(sanRequest $req)
    {
        try
        {
            $districtU=Auth::user()->district;
            $data=$req->validated();
            $privatePath=storage_path('app/private');
            $jsonFilePath=$privatePath . '/output.json';
            // dd($jsonFilePath);
            if($data['district']!=$districtU)
            {   
                return redirect()->back()->withErrors(['error' => 'Selected District does not match with the logged in District']);    
            }
            if(file_exists($jsonFilePath))
            {
                $jsonData = json_decode(file_get_contents($jsonFilePath), true);
                if(isset($jsonData['data'][$data['district']]))
                {
                    if(!isset($jsonData['data'][$data['district']][$data['block']]))
                    {
                        return redirect()->back()->withErrors(['error' => 'Please Select appropriate Gram Panchayat']);    
                    }
                    $ac=$jsonData['data'][$data['district']][$data['block']][$data['gp']];
                   if($ac[0]!=$data['ac'])
                   {
                        return redirect()->back()->withErrors(['error' => 'Assembly Constituency does not match with the Gram Panchayat']);
                   } 
                }
                else
                {
                    return redirect()->back()->withErrors(['error' => 'District does not exists']);
                }
            }
            else
            {
                return redirect()->back()->withErrors(['error' => 'Something Went Wrong']);  
            }
            $sanction=new Sanction();
            $sanction->financial_year=$data['financial_year'];
            $sanction->district=$data['district'];
            $sanction->block=$data['block'];
            $sanction->gp=$data['gp'];
            $sanction->newGP=$req['newGP'];
            $sanction->san_amount=$data['san_amount'];
            $sanction->sanction_date=$data['sanction_date'];
            $sanction->sanction_head=$data['sanction_head'];
            $sanction->sanction_purpose=$data['sanction_purpose'];
            $sanction->ac=$data['ac'];
            $sanction->added_by='district';
            $sanction->save();
            return redirect(url('district/view-sanction'))->with("message","Sanction added successfully!");
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function viewSanction($data=null)
    {
        try{    
            $districtU=Auth::user()->district;
            if($data==null)
            {
                $sanction=Sanction::with('progress')->orderBy('created_at','DESC')->where('district',$districtU)->where('added_by','district')->get();
                return view('District/viewSanction',compact('sanction'));
            }
            elseif($data=='freeze')
            {
                $sanction = Sanction::with(['progress' => function ($query) {
                    $query->where('isFreeze', 'yes');
                }])->whereHas('progress', function ($query) {
                    $query->where('isFreeze', 'yes');
                })->where('district',$districtU)->where('added_by','district')->get();
                return view('Directorate/view',compact('sanction'));
            }
            elseif($data='newgp')
            {
                $sanction=Sanction::where('newGP','yes')->where('district',$districtU)->where('added_by','district')->with('progress')->get();
                return view('Directorate/view',compact('sanction'));
            }
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try
        {
            $privatePath=storage_path('app/private');
            $jsonFilePath=$privatePath . '/output.json';
            $districtU=Auth::user()->district;
            $blocks='';

            if(file_exists($jsonFilePath))
            {
                $jsonData = json_decode(file_get_contents($jsonFilePath), true);
                $blocks=$jsonData['data'][$districtU];
            }
            else
            {
                return redirect()->back()->withErrors(['error' => 'Something went wrong']);
            }
        
            if($id==null)
            {
                return redirect()->back()->withErrors(['error' => 'Id can not be null']);
            }
            $sanction = Sanction::where('added_by','district')
                    ->where('district',$districtU)
                    ->find($id);
            if($sanction->count()===0)
            {
                return redirect()->back()->withErrors(['error' => 'No sanction found with this ID']);
            }
            // dd($blocks);
            return view('District/editSanction',compact('sanction','blocks'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateSanction(sanRequest $req,$sanction_id)
    {
        try
        {
            $districtU=Auth::user()->district;
            if($sanction_id===null)
            {
                return redirect()->back()->withErrors(['error' => 'Sanction Id can not be null']);
            }
            $data=$req->validated();
            if($districtU!==$data['district'])
            {
                return redirect()->back()->withErrors(['error' => 'District does not watch']);
            }
            $sanction=Sanction::find($sanction_id);
            if($sanction->count()===0)
            {
                return redirect()->back()->withErrors(['error' => 'No data found with this sanction']);
            }
            $privatePath = storage_path('app/private');
            $jsonFilePath = $privatePath . '/output.json';
            if(file_exists($jsonFilePath))
            {
                // Read the contents of the JSON file
                $jsonData = json_decode(file_get_contents($jsonFilePath), true);
                // dd($jsonData);
                 // Parse the JSON contents
                // $jsonData = json_decode($jsonContents, true);
                if(isset($jsonData['data'][$data['district']]))
                {
                    if(!isset($jsonData['data'][$data['district']][$data['block']]))
                    {
                        return redirect()->back()->withErrors(['error' => 'Please Select appropriate Gram Panchayat']);    
                    }
                    $ac=$jsonData['data'][$data['district']][$data['block']][$data['gp']];
                   if($ac[0]!=$data['ac'])
                   {
                        return redirect()->back()->withErrors(['error' => 'Assembly Constituency does not match with the Gram Panchayat']);
                   } 
                }
                else
                {
                    return redirect()->back()->withErrors(['error' => 'District does not exists']);
                }
            }
            $sanction->financial_year=$data['financial_year'];
            $sanction->district=$data['district'];
            $sanction->block=$data['block'];
            $sanction->gp=$data['gp'];
            $sanction->newGP=$req['newGP'];
            $sanction->san_amount=$data['san_amount'];
            $sanction->sanction_date=$data['sanction_date'];
            $sanction->sanction_head=$data['sanction_head'];
            $sanction->sanction_purpose=$data['sanction_purpose'];
            $sanction->ac=$data['ac'];
            $sanction->update();
            return redirect(url('district/view-sanction'))->with("message","Sanction updated successfully!");
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

}
