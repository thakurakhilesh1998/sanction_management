<?php

namespace App\Http\Controllers\RGSA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CSCSanction;
use App\Models\Progress_CSC;
use App\Models\ProgressImgCsc;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Progress\CSCProgressData;
use Illuminate\Support\Facades\Storage;
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

    public function updateFormCSC($gp,$block,$district,$work)
    {
        try
        {
            if($gp!=null && $work!=null && $district!=null && $block!=null)
            {
                $sanction=CSCSanction::where('gp',$gp)->where('work',$work)->where('district',$district)->where('block',$block)->get();
                $progress=Progress_CSC::where('work',$work)->first();
                if($progress->count()===0)
                {
                    return back()->withErrors(['error'=>'No Progress found with this Gram Panchayat']);
                }
                $images=$progress->images;
                return view('XEN.CSC.update-form',compact('progress','images','sanction'));
            }
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error'=>$e->getMessage()]);
        }
    }

    public function changeProgressCSC(Request $request,$id)
    {
         try
        {
            $request->validate([
                'completion_status' => 'required|string',
                'statusImage' => 'nullable|image|max:1000', 
            ]);
        
            $progress=Progress_CSC::findOrFail($id);
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
                     'redirect_url'=>url('xen/view-gp-san-rgsa'.'/'.$progress->district.'/'.$progress->block.'/'.$progress->gp.'/'.$progress->work.'/'.'xen')]);
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
                $image=ProgressImgCsc::where('progress_id', $progress->id)->first();
                if($stage==='Work Started')
                {
                   if($image)
                   {
                    $image->work_started_image=$filename;
                    $image->save();
                   }
                   else
                   {
                    ProgressImgCsc::create([
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
                    ProgressImgCsc::create([
                        'progress_id' => $progress->id,
                        'work_partial_image' => $filename
                    ]);
                   } 
                }
                else if($stage==='Work Completed')
                {
                    $sanctions=CSCSanction::where('work',$progress->work)->where('block',$progress->block)->where('district',$progress->district)->where('gp',$progress->gp)->get();
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
                    ProgressImgCsc::create([
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

    public function uploadUCCSC(Request $request)
    {
        try
        {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:1000', 
                'sanction_id' => 'required|integer|exists:csc_sanction,id',
            ]);
            $file=$request->file('file');
            $filename='uploaded_uc' . time() . '.' . $file->getClientOriginalExtension();
            $privatePath='private/UC'.'/'. $filename;
             // Store the file
            Storage::put($privatePath, file_get_contents($file));
            //  Update the database
            $sanction=CSCSanction::find($request->input('sanction_id'));
            $sanction->uc=$filename;
            $sanction->save();
            return redirect()->back()->with('success', 'File uploaded successfully!');
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateProgressImage(Request $request, $id)
    {
        $progress=Progress_CSC::where('id',$id)->first();
        $request->validate([
            'image_type' => 'required|in:work_started_image,work_partial_image,work_completed_image',
            'new_image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $progressImage = ProgressImgCsc::where('progress_id', $id)->firstOrFail();
        $imageType = $request->image_type;
        
          // Delete old image if exists
        if ($progressImage->$imageType && file_exists(public_path('uploads/images/'.$progressImage->$imageType))) {
            unlink(public_path('uploads/images/'.$progressImage->$imageType));
        }

         // Save new image
        $filename=$progress->gp.'_'.time().'_'.$request->file('new_image')->getClientOriginalName();
        $request->file('new_image')->move(public_path('uploads/images'), $filename);

        $progressImage->$imageType = $filename;
        $progressImage->save();

        return redirect()->back()->with('message', 'Image updated successfully!');
    }

}
