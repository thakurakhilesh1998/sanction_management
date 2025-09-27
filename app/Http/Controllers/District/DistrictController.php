<?php
namespace App\Http\Controllers\District;
use App\Http\Controllers\Controller;
use App\Models\Gp_List;
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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DistrictController extends Controller
{

    public function dashboard()
    {
        try
        {
            $district=Auth::user()->district;
            $sanctionCount=Sanction::where('district', $district)->count();
            $totalFundRecived=Sanction::where('district', $district)->sum('san_amount');
            $notreportedSan=Sanction::where('district', $district)->where('status',null)->count();
            
            return view('District.dashboard',compact('sanctionCount','totalFundRecived','notreportedSan'));
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
    
    public function uploadSignedSanction(Request $request)
    {
        try
        {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:1000', // Adjust mime types as needed
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
            return redirect()->back()->with('success', 'File uploaded successfully!');
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
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
            // $sanction->status=$req['status'];
            if($req['status']=='-1')
            {
                dd($req['status']);
                return redirect()->back()->withErrors(['error' => 'Please select Status']);
            }
            else
            {
                $sanction->status=$req['status'];
            }

            $pdf=Pdf::loadView('District.pdftemplate',compact('data'));

            try
            {
                $pdf_filename='sanction_order'.time().'.pdf';
                $pdf_filepath1=$privatePath.'/'.$pdf_filename;

                file_put_contents($pdf_filepath1,$pdf->output());
                $sanction->san_pdf=$pdf_filename;
                $sanction->save();
                return redirect(url('district/view-sanction'))->with("message","Sanction added successfully!");

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

    public function viewSanctionDir()
    {
        $district=Auth::user()->district;
        $sanction=Sanction::where('district',$district)->get();
        return view('District/viewSanctionDir',compact('sanction'));
    }

    public function updateStatus(Request $req)
    {
        $req->validate(
            [
                'id'=>'required|integer',
                'status'=>'required|in:xen,bdo,gp'
            ]);
            $sanction=Sanction::find($req->id);
            $sanction->status=$req->status;
            $sanction->save();

            return response()->json(['success'=>true]);
    }

    public function viewBlockStatus()
    {
        try
        {
            $district=Auth::user()->district;
            $blocks = Gp_List::select('block_name')
            ->selectRaw('COUNT(DISTINCT gp_list.id) as total_gps')
            ->selectRaw('SUM(CASE WHEN pghar_image.gp_id IS NOT NULL THEN 1 ELSE 0 END) as uploaded_count')
            ->leftJoin('pghar_image', 'gp_list.id', '=', 'pghar_image.gp_id')
            ->where('district_name', $district)
            ->groupBy('block_name')
            ->get();
            return view('District/GpGharStatus/view-blocks',compact('blocks'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function getGPStatus($block)
    {
        try
        {
            $gps = Gp_List::select('gp_name', 'block_name')
            ->selectRaw('COUNT(gp_list.id) as total_gps') // No need for DISTINCT on ID as it’s already unique
            ->selectRaw('SUM(CASE WHEN pghar_image.gp_id IS NOT NULL THEN 1 ELSE 0 END) as uploaded_count')
            ->leftJoin('pghar_image', 'gp_list.id', '=', 'pghar_image.gp_id')
            ->where('block_name', $block)
            ->groupBy('gp_name', 'block_name')
            ->get();
            return view('District/GpGharStatus/view-gp',compact('gps','block'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
       
    }

    public function getGPPData($gp,$block)
    {
        try{
        $gpDetails=Gp_List::where('gp_name',$gp)
                    ->where('block_name',$block)
                    ->with('pghar_image')->first();
        return view('District/GpGharStatus/view-info',compact('gpDetails'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);   
        }
        
    }

    public function changeSanction()
    {
        $district=Auth::user()->district;
        $sanction=Sanction::where('district',$district)->get();
        return view('District/changesanction',compact('sanction'));
    }

    public function changeSanctionDist($id)
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
            if($id!=null)        
            {
                $sanction=Sanction::findorfail($id);
                return view('District/change-san-form',compact('sanction','blocks'));
            }   
            else
            {
                return redirect()->back()->withErrors(['error' => 'Sanction Id can not be null']);   
            }
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);   
        }
        
    }
}
