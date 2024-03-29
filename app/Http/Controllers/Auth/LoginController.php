<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    public function authenticated()
    {
        if(Auth::user()->role=="admin")
        {
            return redirect("/admin/dashboard");
        }
        else if(Auth::user()->role=="dir")
        {
            return redirect('/dir/dashboard');
        }
        else if(Auth::user()->role=="district")
        {
            return redirect('/district/dashboard');
        }
        else if(Auth::user()->role=="gp")
        {   
            return redirect('/gp/dashboard');
        }
        else
        {
            return redirect('/');
        }
    }

    public function username()
    {
        return 'username';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    }
