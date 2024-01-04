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
       
        $data=$req->validated();
        $user=new User;
        $user->username=$req['username'];
        $user->email=$req['email'];
        $user->password=Hash::make($req['password']);
        $user->role=$req['role'];
        $user->district=$req['district'];
        $user->save();
        return redirect(url('admin/user/view'))->with("message","User Created Successfully");
    }

    public function view()
    {
        $user=User::all();
        return view('Admin/Users/View',compact('user'));
    }

    public function edit($id)
    {
        $user=User::find($id);
        return view('Admin/Users/edit',compact('user'));
    }

    public function update(UserUpdateRequest $req,$id)
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
    
    public function changePassword()
    {
        return view('Admin.Users.changepassword');
    }
}
