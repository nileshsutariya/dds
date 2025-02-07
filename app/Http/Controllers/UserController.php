<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dash()
    {
        return view('user.dashboard_user');
    }

    public function profile()
    {
        $user = Auth::guard('user')->user();
        $areas = Area::all();
        return view('user.profile', compact('user', 'areas'));
    }

    public function update_profile(Request $request)
    {
        $userId = Auth::guard('user')->id();
        $user = User::findOrFail($userId);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_no' => 'required|string|max:15',
            'area' => 'required|array',
            'area.*' => 'exists:areas,id', 
        ]);

        $user->update([
            'name' => $request->input('name'),
            'phone_no' => $request->input('phone_no'),
            'password' => $request->filled('password') ? Hash::make($request->input('password')) : $user->password,
            'user_name' => $request->input('user_name'),
            'address' => $request->input('address'),
            'area' => implode(',', $request->input('area')),
        ]);

        return redirect()->route('user.dash')->with('success', 'Profile updated successfully.');
    }
}
