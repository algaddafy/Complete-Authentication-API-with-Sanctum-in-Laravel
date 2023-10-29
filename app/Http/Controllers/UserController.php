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

}
