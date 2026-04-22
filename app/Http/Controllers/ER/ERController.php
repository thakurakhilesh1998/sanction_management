<?php

namespace App\Http\Controllers\ER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ZilaParishad;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ZilaParishadImport;
use App\Models\PanchayatSamiti;
use App\Models\GramPanchayat;

class ERController extends Controller
{
    public function addER()
    {
        return view('District.ER.add-er');
    }

   public function create()
{
    $entries = ZilaParishad::where('district', Auth::user()->district)->latest()->get();
    return view('District.ER.add-zp', compact('entries'));
}

public function storeZP(Request $request)
{
    $request->validate([
        'ward_no' => 'required|integer|min:1',
        'ward_name' => 'required|string|max:100',
        'designation' => 'required',
        'name' => 'required|string|max:150',
        'address' => 'required|string',
        'pincode' => 'required|digits:6',
        'mobile' => 'required|digits:10|unique:zila_parishads,mobile',
        'reservation_status' => 'required'
    ]);

        ZilaParishad::create([
        'district' => Auth::user()->district, // 👈 from login user
        'ward_no' => $request->ward_no,
        'ward_name' => $request->ward_name,
        'designation' => $request->designation,
        'name' => $request->name,
        'address' => $request->address,
        'pincode' => $request->pincode,
        'mobile' => $request->mobile,
        'reservation_status' => $request->reservation_status,
    ]);

    return redirect()->route('add.zp')
        ->with('success', 'Record added successfully');
}

public function destroy($id)
{
    $record = ZilaParishad::findOrFail($id);
    $record->delete();

    return redirect()->route('add.zp')
        ->with('success', 'Record deleted successfully');
}

// Panchayat Samiti
public function createPS()
{
    // Logic to show the form for adding Panchayat Samiti
    $entries = PanchayatSamiti::where('district', Auth::user()->district)->latest()->get();
    return view('District.ER.add-panchayat-samiti', compact('entries'));
}
public function storePS(Request $request)
{
    $request->validate([
        'ps_name' => 'required|string|max:150',
        'ward_no' => 'required|integer|min:1',
        'ward_name' => 'required|string|max:100',
        'designation' => 'required',
        'name' => 'required|string|max:150',
        'address' => 'required|string',
        'pincode' => 'required|digits:6',
        'mobile' => 'required|digits:10|unique:zila_parishads,mobile',
        'reservation_status' => 'required'
    ]);

        PanchayatSamiti::create([
        'district' => Auth::user()->district, // 👈 from login user
        'ps_name' => $request->ps_name,
        'ward_no' => $request->ward_no,
        'ward_name' => $request->ward_name,
        'designation' => $request->designation,
        'name' => $request->name,
        'address' => $request->address,
        'pincode' => $request->pincode,
        'mobile' => $request->mobile,
        'reservation_status' => $request->reservation_status,
    ]);

    return redirect()->route('add.ps')
        ->with('success', 'Record added successfully');
}

public function destroyPS($id)
{
    $record = PanchayatSamiti::findOrFail($id);
    $record->delete();

    return redirect()->route('add.ps')
        ->with('success', 'Record deleted successfully');
}

// Gram Panchayat
public function createGP()
{
    // Logic to show the form for adding Gram Panchayat
    $entries = GramPanchayat::where('district', Auth::user()->district)->latest()->get();
    return view('District.ER.add-gp', compact('entries'));
}

public function storeGP(Request $request)
{
    $request->validate([
        'gp_name' => 'required|string|max:150',
        'ps_name' => 'required|string|max:150',
        'designation' => 'required',
        'name' => 'required|string|max:150',
        'address' => 'required|string',
        'pincode' => 'required|digits:6',
        'mobile' => 'required|digits:10|unique:zila_parishads,mobile',
        'reservation_status' => 'required'
    ]);

        GramPanchayat::create([
        'district' => Auth::user()->district, // 👈 from login user
        'gp_name' => $request->gp_name,
        'ps_name' => $request->ps_name,
        'designation' => $request->designation,
        'name' => $request->name,
        'address' => $request->address,
        'pincode' => $request->pincode,
        'mobile' => $request->mobile,
        'reservation_status' => $request->reservation_status,
    ]);

    return redirect()->route('add.gp')
        ->with('success', 'Record added successfully');

}

public function destroyGP($id)
{
    $record = GramPanchayat::findOrFail($id);
    $record->delete();

    return redirect()->route('add.gp')
        ->with('success', 'Record deleted successfully');
}

}