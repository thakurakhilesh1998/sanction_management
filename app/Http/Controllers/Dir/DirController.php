<?php

namespace App\Http\Controllers\Dir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Sanction\sanRequest;
use App\Models\Sanction;
use App\Models\Progress;
use Illuminate\Support\Facades\Hash;
class DirController extends Controller
{

    public function dashboard()
    {
        try
        {
            $sanctionCount=Sanction::count();
            $completedSanction=Progress::where('p_isComplete','yes')->count();
            $totalFundReleased = Sanction::sum('san_amount');
            $totalNewGPs=Sanction::where('newGP','yes')->count();
            $sumUtilized=Sanction::whereHas('progress',function($query)
            {
                $query->where('isFreeze','yes');
            })->sum('san_amount');
            return view('Directorate/dashboard',compact('sanctionCount','completedSanction','totalFundReleased','sumUtilized','totalNewGPs'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
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
            $sanction->save();
            return redirect(url('dir/view'))->with("message","Sanction added successfully!");
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
                $sanction=Sanction::with('progress')->orderBy('created_at','DESC')->get();
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
            return redirect(url('dir/view'))->with("message","Sanction updated successfully!");
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }

    public function viewProgress($data=null)
    {
        try
        {
            $districts = Sanction::distinct()->pluck('district');
            $sanctions = Sanction::with('progress')->get();
            return view('Directorate.view-progress', compact('districts', 'sanctions'));
        }
        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
        
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
                if($block->count()===0)
                {
                    return redirect()->back()->withErrors(['error' => 'Block Data not found']);
                }
                $sanctions=Sanction::where('district',$district)->with('progress')->get();
                if($sanction->count()===0)
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
        $gps = Sanction::where('block', $block)->distinct()->pluck('gp');
        $sanctions=Sanction::where('block',$block)->with('progress')->get();
        return view('Directorate.view-progress-gp',compact('gps','sanctions'));
    }

    public function showGpDetails($gp)
    {
        $gpDetails=Sanction::where('gp',$gp)->with('progress')->get();
        return view('Directorate.gpdetails',compact('gpDetails'));
    }

    public function changePassword()
    {
        return view('Directorate.changepassword');
    }

    public function updatePassword(Request $req)
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

            return redirect(url('dir/change-password'))->with('message', 'Password changed successfully.');
        }

        return back()->withErrors(['current_password' => 'The provided current password is incorrect.']);
    }
}
