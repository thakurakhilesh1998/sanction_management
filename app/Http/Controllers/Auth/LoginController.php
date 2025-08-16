<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function showLoginForm()
    {
        $num1 = rand(10, 19);
        $num2 = rand(1, 10);
        session(['captcha_answer' => $num1 + $num2]);
        return view('auth.login', compact('num1', 'num2'));
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->session_id && Session::getId() !== $user->session_id) {
            Session::getHandler()->destroy($user->session_id);
        }

        $user->session_id = Session::getId();
        $user->save();

        switch ($user->role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'dir':
                return redirect('/dir/dashboard');
            case 'district':
                return redirect('/district/dashboard');
            case 'gp':
                return redirect('/gp/dashboard');
            case 'xen':
                return redirect('/xen/dashboard');
            default:
                return redirect('/');
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'iv' => 'required|string',
            'tag' => 'required|string',
            'captcha' => 'required|integer',
        ]);
    
        if ($request->captcha != session('captcha_answer')) {
            return redirect()->back()->withErrors(['captcha' => 'The CAPTCHA answer is incorrect.']);
        }
    
        $decryptedPassword = $this->decryptPassword($request->password, $request->iv, $request->tag);
    
        if (Auth::attempt(['username' => $request->username, 'password' => $decryptedPassword])) {
            return $this->authenticated($request, Auth::user());
        }
    
        return back()->withErrors(['username' => 'Invalid credentials.']);
    }
    
    protected function decryptPassword($encryptedPassword, $iv, $tag)
    {
        $ciphertext = base64_decode($encryptedPassword);
        $iv = base64_decode($iv);
        $tag = base64_decode($tag);
    
        $key = '0d78c5f79ece7388c918eac45a7aad89';
    
        return openssl_decrypt($ciphertext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
    }
    

    public function username()
    {
        return 'username';
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->session_id = null;
            $user->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
