<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Sanction;
use App\Models\Progress;


class AdminController extends Controller
{
    public function index()
    {
        return view('Admin/index');
    }

    public function dashboard()
    {
        try
        {      
            $user=Auth::user()->count();
            return view('Admin.Users.dashboard',compact('user'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' =>$e->getMessage()]);
        }
       
    }

    public function manageSanction()
    {
        $sanctions=Sanction::with('progress')->orderBy('created_at', 'desc')->get();
        return view('Admin.viewSanction',compact('sanctions'));
    }

    public function deleteSanction($id)
    {
        $sanction = Sanction::findOrFail($id);
        if(!$sanction) {
            return response()->json(['error' => 'Sanction not found'], 404);
        }
        $sanction->delete();
        return response()->json(['success' => true]);
    }

    public function editSanction($id)
    {
        $sanction=Sanction::findOrFail($id);
        return view('Admin.editSanction',compact('sanction'));
    }

    public function updateSanction(Request $request,$id)
    {
        $request->validate([
            'financial_year' => 'required',
            'district'        => 'required',
            'block'           => 'required',
            'gp'              => 'required',
            'newGP'           => 'required|in:Yes,No',
            'san_amount'      => 'required|numeric',
            'sanction_date'   => 'required|date',
            'sanction_head'   => 'required',
            'sanction_purpose'=> 'required',
            'status'          => 'nullable',
            'deleteuc'         => 'nullable|in:yes,no,-1',
        ]);
        $sanction=Sanction::findOrFail($id);

         if ($request->deleteuc === 'yes') {
            $sanction->uc = null; // clear UC column
        }

        $sanction->update([
        'financial_year'  => $request->financial_year,
        'district'         => $request->district,
        'block'            => $request->block,
        'gp'               => $request->gp,
        'newGP'            => $request->newGP,
        'san_amount'       => $request->san_amount,
        'sanction_date'    => $request->sanction_date,
        'sanction_head'    => $request->sanction_head,
        'sanction_purpose' => $request->sanction_purpose,
        'status'           => $request->status, 
        ]);

        return redirect('admin/manage-sanction')->with('success', 'Sanction updated successfully');
    }

    public function manageProgress()
    {
        $progresses = Progress::with('sanction')->orderBy('created_at', 'desc')->get();
        return view('Admin.viewProgress', compact('progresses'));
    }
 
}
