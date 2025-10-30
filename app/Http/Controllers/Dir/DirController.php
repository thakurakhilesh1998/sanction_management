<?php

namespace App\Http\Controllers\Dir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Sanction\sanRequest;
use App\Models\Sanction;
use App\Models\Progress;
use App\Models\Gp_List;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;  
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SanctionAddedMail;

class DirController extends Controller
{

    public function dashboard()
    {
        try
        {
            $sanctionCount = Sanction::distinct('gp', 'block', 'district')
            ->count('gp');
            $totalFundReleased = Sanction::sum('san_amount');
            $totalCompletedWorks=Progress::where('completion_percentage','Work Completed')->count();
            $totalNewGPs=Sanction::distinct('gp', 'block', 'district')->where('newGP','yes')->count();
            $sumUtilized=Sanction::whereNotNull('uc')->sum('san_amount');

            // Count the delay 
            $progressNotUpdatedCount=Progress::where('updated_at','<',Carbon::now()->subDays(100))
                                        ->where('completion_percentage','!=','Work Completed')
                                        ->count();
            $sanctionWithoutProgress=Sanction::whereDoesntHave('progress')
                                    ->where('created_at','<',Carbon::now()->subDays(100))
                                    ->count();
            $totalDelayDays=$progressNotUpdatedCount+$sanctionWithoutProgress;                               
            
            return view('Directorate/dashboard',compact('sanctionCount','totalFundReleased','sumUtilized','totalNewGPs','totalCompletedWorks','totalDelayDays'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function viewNewGPSanction($filter)
    {
        if($filter=='newGp')
        {
            $totalNewGPs=Sanction::distinct('gp', 'block', 'district')->where('newGP','yes')->with('progress')->get();
            return view('Directorate.Dashboard.newGpSan',compact('totalNewGPs','filter'));
        }
        else if($filter=='delay')
        {
           $progressNotUpdatedSanctions=Sanction::whereHas('progress',function($query)
            {
                $query->where('updated_at','<',Carbon::now()->subDays(100))
                ->where('completion_percentage','!=','Work Completed');
            });

            $sanctionWithoutProgress=Sanction::whereDoesntHave('progress')  
                                     ->where('created_at','<',Carbon::now()->subDays(100));
            $totalNewGPs=$progressNotUpdatedSanctions->union($sanctionWithoutProgress)->get();
            return view('Directorate.Dashboard.newGpSan',compact('totalNewGPs','filter'));
        }
     
    }
    public function index()
    {
        return view('Directorate/index');
    }
    public function store(sanRequest $req)
    {
        try
        {   
            $data=$req->validated();
            // $jsonFilePath=public_path('assets/json/output.json');
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
                // Now $jsonData contains the data from the JSON file
                 // You can use it as needed in your controller logic
            }
            else
            {
                return redirect()->back()->withErrors(['error' => 'Something Went Wrong']);  
            }

            $sanction=new Sanction;
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
            $sanction->added_by='directorate';
            $district_data=$sanction->district;
            // $sanction->save();
            // Sanction in PDF form
           
            // Define some predefined text
            $predefinedText = "Thank you for submitting your information. Here are the details you provided:";

             // Load the data into a view for PDF generation
            $pdf = Pdf::loadView('Directorate.pdftemplate', compact('data'));

            // Return the generated PDF as a download
            // return $pdf->download('form_submission.pdf');
            try
            {
                // $pdf_filename='sanction_order'.time().'.pdf';
                // $pdf_filepath1=$privatePath.'/'.$pdf_filename;
                // // Save the PDF to the server
                // file_put_contents($pdf_filepath1, $pdf->output());
                // $sanction->san_pdf=$pdf_filename;
                $sanction->save();
                return redirect(url('dir/view'))->with("message","Sanction added successfully!");
            }
            catch(\Exception $e)
            {
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);    
            }

           
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function view($data=null)
    {
        try{    
            if($data==null)
            {
                $sanction=Sanction::with('progress')->orderBy('created_at','desc')->get();
                return view('Directorate/view',compact('sanction'));
            }
            elseif($data=='freeze')
            {
                $sanction = Sanction::with(['progress' => function ($query) {
                    $query->where('isFreeze', 'yes');
                }])->whereHas('progress', function ($query) {
                    $query->where('isFreeze', 'yes');
                })->get();
                return view('Directorate/view',compact('sanction'));
            }
            elseif($data='newgp')
            {
                $sanction=Sanction::where('newGP','yes')->with('progress')->get();
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
            if($id===null)
            {
                return redirect()->back()->withErrors(['error' => 'Id can not be null']);
            }
            $sanction=Sanction::find($id);
            if($sanction->count()===0)
            {
                return redirect()->back()->withErrors(['error' => 'No sanction find with this ID']);
            }
            return view('Directorate/edit',compact('sanction'));
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function update(sanRequest $req,$sanction_id)
    {
        try
        {
            if($sanction_id===null)
            {
                return redirect()->back()->withErrors(['error' => 'Sanction Id can not be null']);
            }
            $data=$req->validated();
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

            // $pdf = Pdf::loadView('Directorate.pdftemplate', compact('data'));
            // $pdf_filename='sanction_order'.time().'.pdf';
            // $pdf_filepath1=$privatePath.'/'.$pdf_filename;
            try
            {
                // // Delete old file from the server
                // if($sanction->san_pdf)
                // {
                //     $old_path=$privatePath.'/'.$sanction->san_pdf;
                //     if(file_exists($old_path))
                //     {
                //         unlink($old_path);
                //     }
                // }
                //   // Save the new PDF file
                //   file_put_contents($pdf_filepath1, $pdf->output());
                //   $sanction->san_pdf=$pdf_filename;
                $sanction->update();
                return redirect(url('dir/view'))->with("message","Sanction updated successfully!");
            }
            catch(\Exception $e)
            {
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);    
            }           
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }
    // Start of Progress
    public function viewProgress($data=null)
    {
        try
        {
            $sanctions = Sanction::select('sanction.district') // Select district
                        ->selectRaw('COUNT(*) as total_sanctions') // Count all sanctions in the district
                        ->selectRaw('COUNT(CASE WHEN sanction.uc IS NOT NULL THEN 1 END) as utilized_sanctions') // Count utilized sanctions
                        ->selectRaw('SUM(sanction.san_amount) as total_sanction_amount') // Sum of all sanction amounts per district
                        ->selectRaw('SUM(CASE WHEN sanction.uc IS NOT NULL THEN sanction.san_amount ELSE 0 END) as utilized_amount') // Sum of utilized sanction amounts
                        ->groupBy('sanction.district') // Group by district
                        ->get();

            return view('Directorate.Progress.view-progress', compact('sanctions'));
        }
        catch (\Exception $e)
        {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function viewBlockProgress($district)
    {
        try
        {
            $blockSanctions = Sanction::select('sanction.block') // Select block instead of district
                        ->selectRaw('COUNT(*) as total_sanctions')
                        ->selectRaw('SUM(sanction.san_amount) as total_sanction_amount')
                        ->selectRaw('COUNT(CASE WHEN sanction.uc IS NOT NULL THEN 1 END) as utilized_sanctions')
                        ->selectRaw('SUM(CASE WHEN sanction.uc IS NOT NULL THEN sanction.san_amount ELSE 0 END) as utilized_amount')
                        ->where('sanction.district', $district) // Filter by the district if you want to show blocks for a particular district
                        ->groupBy('sanction.block') // Group by block
                        ->get();
            return view('Directorate.Progress.view-progress-block', compact('blockSanctions','district'));
        }
        catch (\Exception $e)
        {
            dd($e->getMessage());
        }
        
    }

    public function viewGPProgress($block,$district)
    {
        $gpSanction = Sanction::select('sanction.gp') // Select Gram Panchayat name
                    ->selectRaw('COUNT(*) as total_sanctions')
                    ->selectRaw('SUM(sanction.san_amount) as total_sanction_amount')
                    ->selectRaw('COUNT(CASE WHEN sanction.uc IS NOT NULL THEN 1 END) as utilized_sanctions')
                    ->selectRaw('SUM(CASE WHEN sanction.uc IS NOT NULL THEN sanction.san_amount ELSE 0 END) as utilized_amount')
                    ->where('sanction.block', $block) 
                    ->where('sanction.district',$district)
                    ->groupBy('sanction.gp') // Group by Gram Panchayat name
                    ->get();
        return view('Directorate.Progress.view-progress-gp', compact('gpSanction','block'));
    }

    public function viewGpDetails($gp,$block)
    {
        $sanctionsForGP=Sanction::where('gp',$gp)->with('progress')->get();
        $completion=0;
        $days=0;
        $delayNotReported=0;
        if($sanctionsForGP[0]->progress!==null)
        {
            $lastUpdateDate=\Carbon\Carbon::parse($sanctionsForGP[0]->progress->updated_at);
            $currentDate=\Carbon\Carbon::now();
            $days=$lastUpdateDate->diffInDays($currentDate);
            $completion=$sanctionsForGP[0]->progress->completion_percentage;    
        }
        else
        {
            $completion="Not Reported";
        }
        return view('Directorate.Progress.view-gpDetails',compact('sanctionsForGP','completion','days'));
    }

    public function getBlocks($district)
    {
        try
        {

            if($district===null)
            {
                return redirect()->back()->withErrors(['error' => 'District can not be null']);
            }
            else
            {
                $blocks=Sanction::where('district',$district)->distinct()->pluck('block');
                if($blocks->count()===0)
                {
                    return redirect()->back()->withErrors(['error' => 'Block Data not found']);
                }
                $sanctions=Sanction::where('district',$district)->with('progress')->get();
                if($sanctions->count()===0)
                {
                    return redirect()->back()->withErrors(['error' => 'District Data not found']);
                }
                return view('Directorate.view-progress-block',compact('blocks','sanctions'));
            }
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        
    }      
    
    public function getGps($block)
    {
        try
        {
            if($block===null)
            {
                return redirect()->back()->withErrors(['error' =>'Block can not be null']);
            }
            $gps = Sanction::where('block', $block)->distinct()->pluck('gp');
            if($gps->count()==0)
            {
                return redirect()->back()->withErrors(['error' =>'Sanctions Not found']);
            }
            $sanctions=Sanction::where('block',$block)->with('progress')->get();
            if($sanctions->count()==0)
            {
                return redirect()->back()->withErrors(['error' =>'Sa Not found']);
            }
            return view('Directorate.view-progress-gp',compact('gps','sanctions'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function showGpDetails($gp,$block,$district)
    {
        try
        {
          if($gp===null || $block===null ||$district===null)
          {
            return redirect()->back()->withErrors(['error' =>'GP,Block and District can not be null']);
          }
            $gpDetails=Sanction::where('gp',$gp)->where('block',$block)->where('district',$district)->with('progress')->get();
            $progress=Progress::where('gp',$gp)->where('block',$block)->where('district',$district)->with('image')->first();
            if($gpDetails->count()===0)
            {
                return redirect()->back()->withErrors(['error' =>'Details Not found']);
            }
            return view('Directorate.gpdetails',compact('gpDetails','progress'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function changePassword()
    {
        return view('Directorate.changepassword');
    }
    public function updatePassword(Request $req)
    {
       try{
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

            return redirect(url('dir/change-password'))->with('message', 'Password changed successfully.');
        }
        return redirect()->back()->withErrors(['current_password' => 'The provided current password is incorrect.']);
    }
    catch (\Exception $e)
    {
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
    }

    public function viewPImage()
    {

        $districts = Gp_List::select('district_name')
        ->selectRaw('COUNT(DISTINCT gp_list.id) as total_gps')
        ->selectRaw('SUM(CASE WHEN pghar_image.gp_id IS NOT NULL THEN 1 ELSE 0 END) as uploaded_count')
        ->leftJoin('pghar_image', 'gp_list.id', '=', 'pghar_image.gp_id')
        ->groupBy('district_name')
        ->get();
        
        return view('Directorate.GPPGharStatus.view-pimage',compact('districts'));
    }

    public function viewBlockGp($district)
    {
    $blocks = Gp_List::select('block_name')
        ->selectRaw('COUNT(DISTINCT gp_list.id) as total_gps')
        ->selectRaw('SUM(CASE WHEN pghar_image.gp_id IS NOT NULL THEN 1 ELSE 0 END) as uploaded_count')
        ->leftJoin('pghar_image', 'gp_list.id', '=', 'pghar_image.gp_id')
        ->where('district_name', $district)
        ->groupBy('block_name')
        ->get();
        
        return view('Directorate.GPPGharStatus.view-blocks',compact('blocks'));
    }

    public function getGPStatus($block)
    {
        $gps = Gp_List::select('gp_name', 'block_name')
        ->selectRaw('COUNT(gp_list.id) as total_gps') // No need for DISTINCT on ID as it’s already unique
        ->selectRaw('SUM(CASE WHEN pghar_image.gp_id IS NOT NULL THEN 1 ELSE 0 END) as uploaded_count')
        ->leftJoin('pghar_image', 'gp_list.id', '=', 'pghar_image.gp_id')
        ->where('block_name', $block)
        ->groupBy('gp_name', 'block_name')
        ->get();
        return view('Directorate.GPPGharStatus.view-gps',compact('gps','block'));    
    }

    public function getGPPData($gp,$block)
    {
        $gpDetails=Gp_List::where('gp_name',$gp)
                    ->where('block_name',$block)
                    ->with('pghar_image')->first();
        return view('Directorate.GPPGharStatus.gp_pghar_details',compact('gpDetails'));
    }


    public function viewSanction(Request $req)
    {
        $sanctions = Sanction::where('district',$req['district'])->where('block',$req['block'])->where('gp',$req['gp'])->get();
        if($sanctions->isEmpty())
        {
            return response()->json(['message' => 'No previous sanction found for the specified parameters'], 404);
        }
        return response()->json($sanctions);
    }

    public function uploadSignedSanction(Request $request)
    {
        try
        {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:5000', // Adjust mime types as needed
                'sanction_id' => 'required|integer|exists:sanction,id',
            ]);
            $file = $request->file('file');
            $filename = 'signed_sanction_' . time() . '.' . $file->getClientOriginalExtension();
            $privatePath = 'private/' . $filename;
             // Store the file
            Storage::put($privatePath, file_get_contents($file));
            //  Update the database
            $sanction=Sanction::find($request->input('sanction_id'));
            $sanction->san_sign_pdf=$filename;
            $sanction->save();

            // Send email to the District

            $gpName=$sanction->gp ?? "Not Available";
            $blockName=$sanction->block ?? "Not Available";

            Mail::to('thakurakhileshm21@gmail.com')->send(new SanctionAddedMail($gpName,$blockName));
            return redirect()->back()->with('success', 'File uploaded successfully and mail sent successfully!');
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    // Dasbhoard Controller
    public function viewCompletedWork()
    {
        $completedWorks=Sanction::with(['progress'=>function($query){
            $query->where('completion_percentage','Work Completed');
        }])
        ->select('gp','block','district')
        ->distinct()
        ->get();
        $iscompletedWorks=0;
        foreach($completedWorks as $work)
        {
            if($work->progress!=null)
            {
                if($work->progress->completion_percentage=='Work Completed')
                {
                    $iscompletedWorks=1;
                    break;
                }
            }
        }
        return view('Directorate.Dashboard.completed',compact('completedWorks','iscompletedWorks'));
    }

    public function viewXenReport()
    {
        
    }
}
