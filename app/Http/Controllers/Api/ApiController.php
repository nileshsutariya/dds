<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Login;

class ApiController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'user_name' =>
            'required|string|min:8|unique:users,user_name|regex:/^[a-zA-Z0-9_]{4,}$/',
            'phone_no' => 'required',
            'password' => 'required',
            'address' => 'required',
            'area' => 'required|array',
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->phone_no = $request->phone_no;
        $user->user_name = $request->user_name;
        $user->address = $request->address;
        if (!empty($request->area)) {
            $user->area = is_array($request->area) ? implode(',', $request->area) : $request->area;
        } else {
            $user->area = '';
        }
        $user->password = Hash::make($request->password);
        $user->status = $request['status'] ? 1 : 0;

        $user->save();

        return response()->json([
            'sucess' => true,
            'data' => [$user]
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'user_name' =>
            'required|string|min:8|unique:users,user_name|regex:/^[a-zA-Z0-9_]{4,}$/',
            'phone_no' => 'required',
            'password' => 'required',
            'address' => 'required',
            'area' => 'required|array',
        ]);

        $update = User::find($request->id);

        $update->name = $request->name;
        $update->phone_no = $request->phone_no;
        $update->user_name = $request->user_name;
        $update->address = $request->address;
        if (!empty($request->area)) {
            $update->area = is_array($request->area) ? implode(',', $request->area) : $request->area;
        } else {
            $update->area = '';
        }
        $update->password = Hash::make($request->password);
        $update->status = $request['status'] ? 1 : 0;

        $update->save();

        return response()->json([
            'sucess' => true,
            'data' => [$update]
        ]);
    }

    public function index()
    {
        $logins = Login::all();

        return response()->json([
            'sucess' => true,
            'data' => [$logins]
        ]);
    }

    public function store_logins(Request $request)
    {
        $request->validate([
            'phone_no' => 'required|unique:logins,phone_no', 
            'password' => 'required',
            'name' => 'required|string|max:255' 
        ]);

        $login = new Login();
        $login->name = $request->name;
        $login->phone_no = $request->phone_no;
        $login->password = Hash::make($request->password);

        if ($login->save()) {
            $token = $login->createToken('AuthToken')->accessToken;
            
            return response()->json([
                'success' => true,
                'data' => $login,
                'token' => $token
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'Failed to create login.'
        ], 500);
    }
    

    // public function update_logins(Request $request)
    // {
    //     $request->validate([
    //         'phone_no' => 'required',
    //         'password' => 'required',
    //         'name' => 'required'
    //     ]);

    //     $login_update = Login::find($request->id);

    //     $login_update->name = $request->name;
    //     $login_update->phone_no = $request->phone_no;
    //     $login_update->password = Hash::make($request->password);
    //     $login_update->save();

    //     return response()->json([
    //         'sucess' => true,
    //         'data' => [$login_update]
    //     ]);
    // }

}
