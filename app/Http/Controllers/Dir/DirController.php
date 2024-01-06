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

    public function index()
    {
        return view('Directorate/index');
    }
    public function store(sanRequest $req)
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
    public function view($data=null)
    {
        // dd($data);

        if($data==null)
        {
            $sanction=Sanction::with('progress')->get();
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
    public function edit($id)
    {
        $sanction=Sanction::find($id);
        return view('Directorate/edit',compact('sanction'));
    }

    public function update(sanRequest $req,$sanction_id)
    {
        $data=$req->validated();
        $sanction=Sanction::find($sanction_id);
        $sanction->financial_year=$data['financial_year'];
        $sanction->district=$data['district'];
        $sanction->block=$data['block'];
        $sanction->gp=$data['gp'];
        $sanction->newGP=$req['newGP'];
        $sanction->san_amount=$data['san_amount'];
        $sanction->sanction_date=$data['sanction_date'];
        $sanction->sanction_head=$data['sanction_head'];
        $sanction->sanction_purpose=$data['sanction_purpose'];
        $sanction->update();
        return redirect(url('dir/view'))->with("message","Sanction updated successfully!");
    }

    public function viewProgress($data=null)
    {
        $districts = Sanction::distinct()->pluck('district');
        $sanctions = Sanction::with('progress')->get();
        return view('Directorate.view-progress', compact('districts', 'sanctions'));
    }

    public function getBlocks($district)
    {
        $blocks=Sanction::where('district',$district)->distinct()->pluck('block');
        $sanctions=Sanction::where('district',$district)->with('progress')->get();
        return view('Directorate.view-progress-block',compact('blocks','sanctions'));
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
