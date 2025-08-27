<?php

namespace App\Http\Controllers\RD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RDSanction;
use App\Models\ProgressRD;
use App\Http\Requests\Progress\RDProgressData;
use App\Models\ProgressRDImage;
use Illuminate\Support\Facades\Storage;

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
            else if($progress->completion_percentage==='-1')
            {
                $progress->completion_percentage=$data['completion_percentage'];
                $progress->p_update=$formatDate;
                $progress->update();
                return redirect('xen/view-block-san'.'/'.$validated['district'].'/'.$validated['block'].'/'.$validated['work'].'/'.'xen')->with('message','Progress Updated Successfully');  
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

    public function updateFormRd($block,$district,$work)
    {
        try
        {
            if($block!=null && $work!=null && $district!=null)
            {
                $sanction=RDSanction::where('district',$district)->where('work',$work)->where('block',$block)->get();
                $progress=ProgressRD::where('work',$work)->first();
                if($progress->count()===0)
                {
                    return back()->withErrors(['error'=>'No Progress found with this Gram Panchayat']);
                }
                $images=$progress->images;
                return view('XEN.RD.update-form',compact('progress','images','sanction'));
            }
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error'=>$e->getMessage()]);
        }
    }

    public function changeProgressRd(Request $request,$id)
    {
           try
        {
            $request->validate([
                'completion_status' => 'required|string',
                'statusImage' => 'nullable|image|max:1000', 
            ]);
        
            $progress=ProgressRD::findOrFail($id);
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
                     'redirect_url'=>url('xen/view-block-san'.'/'.$progress->district.'/'.$progress->block.'/'.$progress->work.'/'.'xen')]);
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
                $image=ProgressRDImage::where('progress_id', $progress->id)->first();
                if($stage==='Work Started')
                {
                   if($image)
                   {
                    $image->work_started_image=$filename;
                    $image->save();
                   }
                   else
                   {
                    ProgressRDImage::create([
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
                    ProgressRDImage::create([
                        'progress_id' => $progress->id,
                        'work_partial_image' => $filename
                    ]);
                   } 
                }
                else if($stage==='Work Completed')
                {
                    $sanctions=RDSanction::where('work',$progress->work)->where('block',$progress->block)->where('district',$progress->district)->get();
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
                    ProgressRDImage::create([
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

    public function uploadUCRD(Request $request)
    {
        try
        {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:1000', // Adjust mime types as needed
                'sanction_id' => 'required|integer|exists:rd_sanction,id',
            ]);
            $file = $request->file('file');
            $filename = 'uploaded_uc' . time() . '.' . $file->getClientOriginalExtension();
            $privatePath = 'private/UC'.'/'. $filename;
             // Store the file
            Storage::put($privatePath, file_get_contents($file));
            //  Update the database
            $sanction=RDSanction::find($request->input('sanction_id'));
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
