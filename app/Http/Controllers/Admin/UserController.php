<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\UserDataRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\UserUpdateRequest;

class UserController extends Controller
{
    public function index()
    {
        return view('Admin/Users/index');
    }

    public function create(UserDataRequest $req)
    {
       try
       {
        $data=$req->validated();
        $user=new User;
        $user->username=$req['username'];
        $user->email=$req['email'];
        $user->password=Hash::make($req['password']);
        $user->role=$req['role'];
        $user->district=$req['district'];
        $user->block_name=$req['block_name'];
        $user->gp_name=$req['gp_name'];
        $user->save();
        return redirect(url('admin/user/view'))->with("message","User Created Successfully");
       }
       catch(\Exception $e)
       {
            return redirect()->back()->withErrors(['error' =>$e->getMessage()]);
       }
       
    }

    public function view()
    {
        try
        {
            $user=User::all();
            return view('Admin/Users/View',compact('user'));
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' =>$e->getMessage()]);
        }
        
    }

    public function edit($id)
    {
        try
        {
            if($id!=null)
            {
                $user=User::find($id);
                if($user->count()===0)
                {
                    return redirect()->back()->withErrors(['error' =>'No user find']);
                }
                return view('Admin/Users/edit',compact('user'));
            }
            else
            {
                return redirect()->back()->withErrors(['error' =>'ID can not be null']);
            }
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
      
    }

    public function update(UserUpdateRequest $req,$id)
    {
        try
        {
            if($id!=null)
            {
                $data=$req->validated();
                $user=User::find($id);
                $user->username=$data['username'];
                $user->email=$data['email'];
                $user->password=$data['password'];
                $update=$user->update();
                if($update)
                {
                    return redirect(url('admin/user/view'))->with('message',"User Data Updated Successfully");
                }
            }
            else
            {
                return redirect()->back()->withErrors(['error' => 'ID can not be null']);
            }
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    public function changePassword()
    {
        return view('Admin.Users.changepassword');
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

                return redirect(url('admin/user/view'))->with('message', 'Password changed successfully.');
            }

            return back()->withErrors(['current_password' => 'The provided current password is incorrect.']);  
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
