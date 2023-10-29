<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=> 'required',
            'email'=> 'required|email',
            'password'=> 'required|confirmed',
            'tc'=>'required',
        ]);
        if(User::where('email',$request->email)->first()){
            return response([
                'message'=> 'Email Exist.',
                'status'=> 'success'
            ],200);
        }
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'tc'=>json_decode($request->tc)
        ]);
        $token = $user->createToken($request->email)->plainTextToken;

        return response([
            'message' => 'Registration Completed.',
            'token' => $token,
            'status' => 'Success'
        ],201);
    }
    
    public function login(Request $request){
        $request->validate([
            'email'=> 'required|email',
            'password'=> 'required|confirmed',
        ]);
        $user = User::where('email',$request->email)->first();

        if($user && Hash::check($request->password,$user->password)){
            $token = $user->createToken($request->email)->plainTextToken;
            return response([
                'token'=>$token,
                'message'=>'Logged In Success.',
                'status'=>'success.',
            ],200);
        }
        return response([
            'message'=>'Credentials incorrect.',
            'status'=>'success.',
        ],200);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message'=>'Logged Out Success.',
            'status'=>'success'
        ],200);
    }

    public function logged_user(){
        $logged_user = auth()->user();
        return response([
            'data' => $logged_user,
            'message'=>'User details.',
            'status'=>'success'
        ],200);
    }

    public function change_password_with_old(Request $request){
        $request->validate([
            'old_password' => 'required',
            'password'=> 'required|confirmed'
        ]);
        $logged_user = auth()->user();
        if(Hash::check($request->old_password,$logged_user->password)){

            $logged_user->password = Hash::make($request->password);
            $logged_user->save();
    
            return response([
                'message'=>'Password Changed.',
                'status' => 'success'
            ]);
        }
        return response([
            'message'=>'Old password dosen\'t match.',
            'status' => 'success'
        ]);
        
        
    }
}
