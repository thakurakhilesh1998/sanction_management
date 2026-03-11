<?php

namespace App\Http\Controllers\RGSA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SanRequestCSC;
use App\Models\CSCSanction;
use Illuminate\Support\Facades\Storage;
class RGSAController extends Controller
{
    public function addCSCSanction()
    {
        return view('Directorate.RGSA.addCSC');
    }

    public function storeCSCSanction(SanRequestCSC $request)
    {
        $data=$request->validated();
        $privatePath = storage_path('app/private');
        $jsonFilePath = $privatePath . '/output.json';
        if(file_exists($jsonFilePath))
        {
            $jsonData=json_decode(file_get_contents($jsonFilePath), true);
            if(isset($jsonData['data'][$data['district']]))
            {
                if (!isset($jsonData['data'][$data['district']][$data['block']][$data['gp']]))
                {
                    return redirect()->back()->withErrors(['error' => 'Please Select appropriate Gram Panchayat']);    
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

        $cscsan=new CSCSanction;
        $cscsan->financial_year=$data['financial_year'];
        $cscsan->district=$data['district'];
        $cscsan->block=$data['block'];
        $cscsan->gp=$data['gp'];
        $cscsan->san_amount=$data['san_amount'];
        $cscsan->sanction_date=$data['sanction_date'];
        $cscsan->sanction_head=$data['sanction_head'];
        $cscsan->sanction_purpose=$data['sanction_purpose'];
        $cscsan->agency=$data['agency'];
        $cscsan->work=$data['block'].' '.$data['sanction_purpose'];
        $cscsan->save();
        return redirect(url('dir/view-csc'))->with("message","Sanction added successfully!");
    }

    public function viewCSCSanction()
    {
        $cscSan=CSCSanction::get();
        return view('Directorate/RGSA/viewCSC',compact('cscSan'));
    }

    public function viewEditPageCSC($id)
    {
        try
        {
            if($id===null)
            {
                return redirect()->back()->withErrors(['error'=>'Id can not be null']);
            }
            $sanction=CSCSanction::find($id);
            if($sanction->count()===0)
            {
                return redirect()->back()->withErrors(['error' => 'No sanction find with this ID']);
            }
            return view('Directorate/RGSA/editCSC',compact('sanction'));
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function updateSanctionCSC(SanRequestCSC $request,$id)
    {
        $data=$request->validated();
        $privatePath = storage_path('app/private');
        $jsonFilePath = $privatePath . '/output.json';
        if(file_exists($jsonFilePath))
        {
            $jsonData=json_decode(file_get_contents($jsonFilePath), true);
            if(isset($jsonData['data'][$data['district']]))
            {
                if (!isset($jsonData['data'][$data['district']][$data['block']][$data['gp']]))
                {
                    return redirect()->back()->withErrors(['error' => 'Please Select appropriate Gram Panchayat']);    
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
        $cscsan=CSCSanction::find($id);
        $cscsan->financial_year=$data['financial_year'];
        $cscsan->district=$data['district'];
        $cscsan->block=$data['block'];
        $cscsan->gp=$data['gp'];
        $cscsan->san_amount=$data['san_amount'];
        $cscsan->sanction_date=$data['sanction_date'];
        $cscsan->sanction_head=$data['sanction_head'];
        $cscsan->sanction_purpose=$data['sanction_purpose'];
        $cscsan->agency=$data['agency'];
        $cscsan->work=$data['block'].' '.$data['sanction_purpose'];
        $cscsan->save();
        return redirect(url('dir/view-csc'))->with("message","Sanction added successfully!");
    }

    public function uploadSignedSanction(Request $request)
    {
        try
        {
            $request->validate([
                'file'=>'required|file|mimes:pdf|max:1000',
                'id'=>'required|integer|exists:csc_sanction,id',
            ]);

            $file=$request->file('file');
            $filename = 'signed_sanction_csc' . time() . '.' . $file->getClientOriginalExtension();
            $privatePath='private/'.$filename;

            Storage::put($privatePath,file_get_contents($file));

            $sanction=CSCSanction::find($request->input('id'));
            $sanction->san_pdf=$filename;
            $sanction->save();
             return redirect(url('dir/view-csc'))->with("message","Sanction uploaded successfully!");
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function viewProgress($district,$block,$gp,$work)
    {
        $sanctionsForGP=CSCSanction::where('district',$district)->where('block',$block)->where('gp',$gp)->where('work',$work)->with('progress_csc')->get();
        $completion=0;
        $days=0;
        $delayNotReported=0;
        if($sanctionsForGP[0]->progress_csc!==null)
        {
            $lastUpdateDate=\Carbon\Carbon::parse($sanctionsForGP[0]->progress_csc->updated_at);
            $currentDate=\Carbon\Carbon::now();
            $days=$lastUpdateDate->diffInDays($currentDate);
            $completion=$sanctionsForGP[0]->progress_csc->completion_percentage;    
        }
        else
        {
            $completion="Not Reported";
        }
        return view('Directorate.RGSA.view-csc-progress',compact('sanctionsForGP','completion','days'));
    }
}
