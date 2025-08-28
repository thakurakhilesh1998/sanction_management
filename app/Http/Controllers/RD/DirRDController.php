<?php

namespace App\Http\Controllers\RD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SanRequestRd;
use App\Models\RDSanction;
use App\Models\ProgressRD;
use Illuminate\Support\Facades\Storage;
class DirRDController extends Controller
{
    public function addSanction()
    {
        return view('Directorate/RD/add_sanction');
    }

    public function store(SanRequestRd $request)
    {
        $data=$request->validated();

        $privatePath = storage_path('app/private');
        $jsonFilePath = $privatePath . '/output.json';

        if(file_exists($jsonFilePath))
        {
            $jsonData=json_decode(file_get_contents($jsonFilePath), true);
            if(isset($jsonData['data'][$data['district']]))
            {
                if(!isset($jsonData['data'][$data['district']][$data['block']]))
                {
                    return redirect()->back()->withErrors(['error' => 'Please Select appropriate Block']);    
                }
            }
            else
            {
                return redirect()->back()->withErrors(['error' => 'Please Select appropriate District']);
            }

        }
        else
        {
            return redirect()->back()->withErrors(['error' => 'Something Went Wrong']);  
        }

        $rdsan=new RDSanction;
        $rdsan->financial_year=$data['financial_year'];
        $rdsan->district=$data['district'];
        $rdsan->block=$data['block'];
        $rdsan->san_amount=$data['san_amount'];
        $rdsan->sanction_date=$data['sanction_date'];
        $rdsan->sanction_head=$data['sanction_head'];
        $rdsan->sanction_purpose=$data['sanction_purpose'];
        $rdsan->agency=$data['agency'];
        $rdsan->work=$data['block'].' '.$data['sanction_purpose'];
        $rdsan->save();
        return redirect(url('dir/view-rd'))->with("message","Sanction added successfully!");
        // return redirect()->back()->with('success', 'Sanction added successfully');
    }

    public function viewSanction()
    {
        $rdsanctions = RDSanction::with('progress_rd')->get();
        return view('Directorate/RD/view_sanction', compact('rdsanctions'));
    }

      public function uploadSignedSanction(Request $request)
    {
        try
        {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:1000', // Adjust mime types as needed
                'id' => 'required|integer|exists:rd_sanction,id',
            ]);
            $file = $request->file('file');
            $filename = 'signed_sanction_rd' . time() . '.' . $file->getClientOriginalExtension();
            $privatePath = 'private/' . $filename;
             // Store the file
            Storage::put($privatePath, file_get_contents($file));
            //  Update the database
            $sanction=RDSanction::find($request->input('id'));
            $sanction->san_pdf=$filename;
            $sanction->save();
            return redirect()->back()->with('success', 'File uploaded successfully!');
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function viewBlockProgress($district,$block,$work)
    {
        $sanctionsForGP=RDSanction::where('district',$district)->where('block',$block)->where('work',$work)->with('progress_rd')->get();
        $completion=0;
        $days=0;
        $delayNotReported=0;
        if($sanctionsForGP[0]->progress_rd!==null)
        {
            $lastUpdateDate=\Carbon\Carbon::parse($sanctionsForGP[0]->progress_rd->updated_at);
            $currentDate=\Carbon\Carbon::now();
            $days=$lastUpdateDate->diffInDays($currentDate);
            $completion=$sanctionsForGP[0]->progress_rd->completion_percentage;    
        }
        else
        {
            $completion="Not Reported";
        }
        return view('Directorate.RD.view-rd-progress',compact('sanctionsForGP','completion','days'));
    }

    public function editSanctionRd($id)
    {
        try
        {
            if($id===null)
            {
                return redirect()->back()->withErrors(['error' => 'Id can not be null']);
            }
            $sanction=RDSanction::find($id);
            if($sanction->count()===0)
            {
                return redirect()->back()->withErrors(['error' => 'No sanction find with this ID']);
            }
            return view('Directorate/RD/edit-sanction',compact('sanction'));
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateSanctionRd(SanRequestRd $req,$id)
    {
        try
        {
            if($id===null)
            {
                return redirect()->back()->withErrors(['error' => 'Sanction Id can not be null']);
            }
            $data=$req->validated();
            $sanction=RDSanction::find($id);
            if($sanction->count===0)
            {
                return redirect()->back()->withErrors(['error' => 'No data found with this sanction']);
            }
            $privatePath = storage_path('app/private');
            $jsonFilePath = $privatePath . '/output.json';

            if(file_exists($jsonFilePath))
            {
                $jsonData=json_decode(file_get_contents($jsonFilePath),true);

                if(isset($jsonData['data'][$data['district']]))
                {
                    if(!isset($jsonData['data'][$data['district']][$data['block']]))
                    {
                        return redirect()->back()->withErrors(['error' => 'Please Select appropriate Details']);    
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
           $sanction->san_amount=$data['san_amount'];
           $sanction->sanction_date=$data['sanction_date'];
           $sanction->sanction_head=$data['sanction_head'];
           $sanction->sanction_purpose=$data['sanction_purpose'];
           $sanction->agency=$data['agency'];
           $sanction->update();
           return redirect(url('dir/view-rd'))->with("message","Sanction updated successfully!");
        }   
        catch (\Exception $e)
        {

        }
    }

}
