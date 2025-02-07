<?php

namespace App\Http\Controllers;

use App\Models\Login;
// use Faker\Provider\Lorem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function view()
    {
        return view('login');
    }


    public function auth(Request $request)
    {
        $request->validate([
            'phone_no' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('phone_no', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dash');
        } elseif (Auth::guard('user')->attempt($credentials)) {
            // print_r('djsbdhjbjh');
            // die;
            return redirect()->route('user.dash');
        } 
        // elseif (Auth::guard('clients')->attempt($credentials)) {
        //     return redirect()->route('client.dash');
        // } 
        else {
            return redirect()->back()->withErrors(['password' => 'Invalid phone number or password']);
        }
        return redirect()->route('login.view')->withErrors(['phone_no' => 'Invalid phone number or password']);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
        } elseif (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } 
        // elseif (Auth::guard('clients')->check()) {
        //     Auth::guard('clients')->logout();
        // }
        return redirect()->route('login.view');
    }
}
