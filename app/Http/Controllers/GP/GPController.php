<?php

namespace App\Http\Controllers\GP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PGharStatus\PGharStatusImg;
use App\Http\Requests\PGharStatus\PGharUpdate;
use Illuminate\Support\Facades\Auth;
use App\Models\Sanction;
use App\Models\Progress;
use App\Models\Gp_List;
use App\Models\Pghar_Image;
use App\Models\Image;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Progress\ProgressData;
use Illuminate\Support\Facades\Storage;

class GPController extends Controller
{
    public function dashboard()
    {
        $gpName=Auth::user()->gp_name;
        $blockName=Auth::user()->block_name;
        $district=Auth::user()->district;
        $sanctionCount=Sanction::where('district',$district)->where('block',$blockName)->where('gp',$gpName)->count();
        return view('GP.dashboard',compact('sanctionCount'));
    }

    public function viewStatus()
    {
        $gpName=Auth::user()->gp_name;
        $block=Auth::user()->block_name;
        $district=Auth::user()->district;
        $gpid=Gp_List::where('gp_name',$gpName)->where('block_name',$block)->where('district_name',$district)->first();
        $gpGhar=Pghar_Image::where('gp_id',$gpid->id)->get();
        if($gpGhar->isNotEmpty())
        {
            return view('GP.pgharstatus_update',compact('gpGhar'));
        }   
        else
        {
            return view('GP.pgharstatus');
        }
        
    }

    public function updateStatus(PGharUpdate $data, $id)
    {
        $validatedStatus=$data->validated();
        $district=Auth::user()->district;
        $block=Auth::user()->block_name;
        $gpName=Auth::user()->gp_name;
        $gp_id=Gp_List::where('district_name',$district)->where('block_name',$block)->where('gp_name',$gpName)->first();
        $dataV=Pghar_Image::find($id);
        if(isset($dataV))
        {
            $dataV->rooms=$validatedStatus['rooms'];
            $dataV->lat=$validatedStatus['lat'];
            $dataV->long=$validatedStatus['long'];
            $dataV->remarks=$validatedStatus['remarks'];
            if($data->hasFile('p_image'))
            {
                $destination='uploads/pghar_images'.$dataV->image_path;
                if(File::exists($destination))
                {
                    File::delete($destination);
                }
                $pimage=$data->file('p_image');
                $filename=$gp_id->id.'_'.time().'_'.$pimage->getClientOriginalName();
                $pimage->move('uploads/pghar_images',$filename);
                $dataV->image_path=$filename;
            }
            $dataV->update();
            return redirect()->back()->with('message',"Data has been updated");
        }

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
            $existingImages=Pghar_Image::where('gp_id',$gp_id->id)->get();

            if($gp_id)
            {
                    $pghar_images=new Pghar_Image();
                    $pghar_images->rooms=$data['rooms'];
                    $pghar_images->lat=$data['lat'];
                    $pghar_images->long=$data['long'];
                    $pghar_images->remarks=$data['remarks'];
                    if($data->hasFile('p_image'))
                    {
                        $pimage=$data->file('p_image');
                        $filename=$gp_id->id.'_'.time().'_'.$pimage->getClientOriginalName();
                        $pimage->move('uploads/pghar_images',$filename);
                        $pghar_images->image_path=$filename;
                    }
                    $pghar_images->gp_id=$gp_id->id;
                    $pghar_images->save();
                    return redirect()->back()->with('message','Data Saved Successfully');  
            
            }
            else
            {
                return redirect()->back()->withErrors(['error' => 'GP ID not found']);
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
            $sanction=Sanction::where('district',$district)
            ->where('block',$block)
            ->where('gp',$gp_name)
            ->where('status','gp')
            ->with('progress')
            ->get();
            return view('GP.view-sanction',compact('sanction'));    
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);;
        }

    }

    public function viewGPSanction($gp,$block,$district)
    {
        try
        {
            $sanction=Sanction::where('district',$district)->where('block',$block)->where('gp',$gp)->with('progress')->get();
            return view('GP/view-gpsanction',compact('sanction'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);;
        }
        
    }



    public function addProgress($gp,$block,$district)
    {
        try
        {
            if($gp==null || $block==null || $district==null)
            {
                return redirect()->back()->withErrors(['error' => 'All fields are required to be entered']);
            }
            else
            {
                $sanction=Sanction::where('district',$district)->where('block',$block)->where('gp',$gp)->get();
                
                if(count($sanction)===0)
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

           // Check if progress already exists with the same gp, block, and district
            $progress = Progress::where('gp', $progressValidated['gp'])
            ->where('block', $progressValidated['block'])
            ->where('district', $progressValidated['district'])
            ->first();
            
            if(!$progress)
            {
                $progress=new Progress;
                $progress->completion_percentage=$data['completion_percentage'];
                $progress->remarks=$progressValidated['remarks'];
                $progress->gp=$progressValidated['gp'];
                $progress->block=$progressValidated['block'];
                $progress->district=$progressValidated['district'];
                $progress->p_update=$formatDate;
                $progress->save();
                return redirect('gp/view-gpsan'.'/'.$progressValidated['gp'].'/'.$progressValidated['block'].'/'.$progressValidated['district'])->with('message',"Progress Added Successfully!");
            }
            else if($progress->completion_percentage==='-1')
            {
                $progress->completion_percentage=$data['completion_percentage'];
                $progress->p_update=$formatDate;
                $progress->update();
                return redirect('gp/view-gpsan'.'/'.$progressValidated['gp'].'/'.$progressValidated['block'].'/'.$progressValidated['district'])->with('message',"Progress Updated Successfully!");
            }
            else
            {
                return redirect('gp/view-gpsan'.'/'.$progressValidated['gp'].'/'.$progressValidated['block'].'/'.$progressValidated['district'])->with('message',"Progress Already added!");
            }
            
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
    
    public function updateProgress($gp,$block,$district)
    {
       try
       {
         if($gp!=null && $block!=null && $district!=null)
         {
           $sanction=Sanction::where('district',$district)->where('block',$block)->where('gp',$gp)->get();
           $progress=Progress::where('gp',$gp)->where('block',$block)->where('district',$district)->first();
         }
         if($progress->count()===0)
         {
             return back()->withErrors(['error' =>'No sanction found with this Gram Panchayat']);
         }
                $images=$progress->image;
                return view('GP.update-form',compact('progress','images','sanction'));
       }
       catch (\Exception $e)
       {
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
       } 
    }

    public function changeProgress(Request $request,$id)
    {
        try
        {
            $request->validate([
                'completion_status' => 'required|string',
                'statusImage' => 'nullable|image|max:400', 
            ]);
        
            $progress=Progress::findOrFail($id);
            $currentStage=$progress->completion_percentage;
            $stage=$request->input('completion_status');
            // Stage order
            $stageOrder=[
                'Tender Floated' => ['Tender Awarded'],
                'Tender Awarded' => ['Work Started'],
                'Work Started' => ['Partial Completion'],
                'Partial Completion' => ['Work Completed'],
                'Work Completed' => []
            ];

            // If Tender Cancelled selected reset the progress
            if($stage==='Tender Cancelled')
            {
                $progress->completion_percentage=-1;
                $progress->save();
                return response()->json(
                    ['message'=>'Tender Cancelled Updated',
                     'redirect_url'=>url('gp/view-gpsan/'.$progress->gp.'/'.$progress->block.'/'.$progress->district)]);
            }

            if(!in_array($stage,$stageOrder[$currentStage]))
            {
                return response()->json(['error'=>'Invalid progress flow.'],400);
            }

            if(in_array($stage,['Work Started', 'Partial Completion', 'Work Completed'])){
                if(!$request->hasFile('status_image'))
                {
                    return response()->json(['error' => 'Image is required for this stage!'], 400);
                }

                $uploadedImage=$request->file('status_image');
                $filename=$filename=$progress->gp.'_'.time().'_'.$uploadedImage->getClientOriginalName();
                $filePath='uploads/images/'.$filename;
                $uploadedImage->move(public_path('uploads/images'),$filename);
                $image=Image::where('progress_id', $progress->id)->first();
                if($stage==='Work Started')
                {
                   if($image)
                   {
                    $image->work_started_image=$filename;
                    $image->save();
                   }
                   else
                   {
                    Image::create([
                        'progress_id' => $progress->id,
                        'work_started_image' => $filename
                    ]);
                   }   
                }
                else if($stage==='Partial Completion')
                {
                    if($image)
                   {
                    $image->work_partial_image=$filename;
                    $image->save();
                   }
                else
                   {
                    Image::create([
                        'progress_id' => $progress->id,
                        'work_partial_image' => $filename
                    ]);
                   } 
                }
                else if($stage==='Work Completed')
                {
                    $sanctions=Sanction::where('gp',$progress->gp)->where('block',$progress->block)->where('district',$progress->district)->get();
                    foreach($sanctions as $san)
                    {
                        if($san->uc==null)
                        { 
                            return response()->json(['error' => 'Please upload UC of all Sanctions before marking the Work as Completed'], 400);
                        }
                    }

                    if($image)
                   {
                    $image->work_completed_image=$filename;
                    $image->save();
                   }
                   else
                   {
                    Image::create([
                        'progress_id' => $progress->id,
                        'work_completed_image' => $filename
                    ]);
                   }   
                }
            }
            $progress->remarks=$request->input('remarks');
            $progress->completion_percentage=$stage;
            $progress->save();
            return response()->json(['message' => 'Progress updated successfully!']);
        }
        catch (\Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 422);
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
   
    public function view($id)
    {
        try
        {
            if($id==null)
            {
                return redirect()->back()->withErrors(['error' =>'ID not be null']);
            }
            $data = Sanction::with('progress.image')->find($id);
            return view('GP.view-progress',compact('data'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function uploadUC(Request $request)
    {
        try
        {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:1000', // Adjust mime types as needed
                'sanction_id' => 'required|integer|exists:sanction,id',
            ]);
            $file = $request->file('file');
            $filename = 'uploaded_uc' . time() . '.' . $file->getClientOriginalExtension();
            $privatePath = 'private/UC' .'/'. $filename;
             // Store the file
            Storage::put($privatePath, file_get_contents($file));
            //  Update the database
            $sanction=Sanction::find($request->input('sanction_id'));
            $sanction->uc=$filename;
            $sanction->save();
            return redirect()->back()->with('success', 'File uploaded successfully!');
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

}
