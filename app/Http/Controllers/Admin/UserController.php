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
        if($req['zone']==='-1')
        {
            $user->zone=null;
        }
        else
        {
            $user->zone=$req['zone'];
        }
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
            return view('Admin/Users/view',compact('user'));
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

    public function update(UserUpdateRequest $req, $id)
{
    try
    {
        if($id != null)
        {
            $data = $req->validated();
            $user = User::findOrFail($id);  // Use findOrFail to handle non-existent users

            // Update username and email
            $user->username = $data['username'];
            $user->email = $data['email'];

            // Check if password is provided and update if needed
            if (!empty($data['password'])) {
                // Ensure password history check and update
                if (!$user->checkPasswordHistory($data['password'])) {
                    return redirect()->back()->withErrors(['password' => 'You cannot use any of your last five passwords.']);
                }
                $user->updatePassword($data['password']);  // Use updatePassword method in User model
            }

            // Save other changes
            if ($user->save()) {
                return redirect(url('admin/user/view'))->with('message', "User Data Updated Successfully");
            }

            return redirect()->back()->withErrors(['error' => 'Failed to update user data.']);
        }
        else
        {
            return redirect()->back()->withErrors(['error' => 'ID cannot be null']);
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

    public function updatePassword(Request $req, User $user)
    {
        try {
            // Decrypt the passwords
            $decryptedCurrentPassword = $this->decryptPassword($req->current_password, $req->iv_current, $req->tag_current);
            $decryptedNewPassword = $this->decryptPassword($req->new_password, $req->iv_new, $req->tag_new);
            $decryptedConfirmPassword = $this->decryptPassword($req->new_password_confirmation, $req->iv_confirm, $req->tag_confirm);

            // Replace encrypted values with decrypted ones for validation
            $req->merge([
                'current_password' => $decryptedCurrentPassword,
                'new_password' => $decryptedNewPassword,
                'new_password_confirmation' => $decryptedConfirmPassword,
            ]);

            // Validate request with decrypted passwords
            $req->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&#]/',
            ]);

            $user = auth()->user();

            // Check if the current password matches
            if (Hash::check($decryptedCurrentPassword, $user->password)) {

                // Check if the new password is not among the recent passwords
                if (!$user->checkPasswordHistory($decryptedNewPassword)) {
                    return back()->withErrors(['new_password' => 'You cannot use any of your last five passwords.']);
                }

                // Update the password and set expiration
                $user->updatePassword($decryptedNewPassword);

                return redirect(url('admin/user/view'))->with('message', 'Password changed successfully.');
            }

            return back()->withErrors(['current_password' => 'The provided current password is incorrect.']);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    private function decryptPassword($encryptedPassword, $iv, $tag)
    {
    // Convert the base64-encoded strings back to binary data
        $ciphertext = base64_decode($encryptedPassword);
        $iv = base64_decode($iv);
        $tag = base64_decode($tag);

        $key = '0d78c5f79ece7388c918eac45a7aad89'; // Your AES key
        $cipher = 'aes-256-gcm';

        return openssl_decrypt($ciphertext, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);
    }
}
