<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ResetPassword;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class ResetPasswordController extends Controller
{
    public function send_email_with_reset_password(Request $request){
        $request->validate([
            'email'=>'required|email'
        ]);
        $email = $request->email;
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response([
                'message' => 'Email not exist.',
                'status' => 'failed'
            ],404);
        }

        $token = Str::random(60);
        ResetPassword::create([
            'email' => $user->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // dd("http://127.0.0.1:3000/api/reset/".$token);

        Mail::send('reset', ['token'=>$token],function(Message $message)use($email){
            $message->subject('Reset your password.');
            $message->to($email);
        });

        return response([
            'message' => 'Resent mail sent ... Check now.',
            'status' => 'success'
        ]);
    }
}
