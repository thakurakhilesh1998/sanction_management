<?php

namespace App\Http\Controllers\Xen;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sanction;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Progress\ProgressData;
use App\Models\Progress;
use App\Models\Image;
use Illuminate\Support\Facades\File;

class XENController extends Controller
{
    public function index()
    {
        try
        {
            $zone=Auth::user()->zone;
            $sanctionQuery=Sanction::where("status",'xen')->with('progress');
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
            $sanction=$sanctionQuery->count();
            return view('XEN.dashboard',compact('sanction'));
        }
        catch(Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);;
        }
        
    }

    public function viewSanciton()
    {
        try
        {
            $zone=Auth::user()->zone;
            $sanctionQuery=Sanction::where('status','xen')->with('progress');
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
            $sanction = $sanctionQuery->get();
            
            return view('XEN/view-sanction',compact('sanction'));
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
            return view('XEN/view-gpsanction',compact('sanction'));
        }
        catch(Exception $e)
        {
            // dd($e->getMessage());
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
            $privatePath = 'private/UC'.'/'. $filename;
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
                return view('XEN.progress',compact('sanction'));    
            }
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);;
        }
    }

    public function saveProgress(ProgressData $data)
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
                return redirect('xen/view-gpsan'.'/'.$progressValidated['gp'].'/'.$progressValidated['block'].'/'.$progressValidated['district'])->with('message',"Progress Added Successfully!");
            }
            else if($progress->completion_percentage==='-1')
            {
                $progress->completion_percentage=$data['completion_percentage'];
                $progress->p_update=$formatDate;
                $progress->update();
                return redirect('xen/view-gpsan'.'/'.$progressValidated['gp'].'/'.$progressValidated['block'].'/'.$progressValidated['district'])->with('message',"Progress Updated Successfully!");
            }
            else
            {
                return redirect('xen/view-gpsan'.'/'.$progressValidated['gp'].'/'.$progressValidated['block'].'/'.$progressValidated['district'])->with('message',"Progress Already added!");
            }
            
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);;
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
             return back()->withErrors(['error' =>'No Progress found with this Gram Panchayat']);
         }
                $images=$progress->image;
                return view('XEN.update-form',compact('progress','images','sanction'));
       }
       catch (Exception $e)
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
                'statusImage' => 'nullable|image|max:1000', 
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
                     'redirect_url'=>url('xen/view-gpsan/'.$progress->gp.'/'.$progress->block.'/'.$progress->district)]);
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
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function viewProgress()
    {

    }
}
