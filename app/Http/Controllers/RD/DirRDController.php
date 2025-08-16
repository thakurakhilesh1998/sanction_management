<?php

namespace App\Http\Controllers\RD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SanRequestRd;
use App\Models\RDSanction;
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
        $rdsanctions = RDSanction::all();
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

}
