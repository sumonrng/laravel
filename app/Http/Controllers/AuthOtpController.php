<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VerificationOtpCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthOtpController extends Controller
{
    public function login()
    {
        return view('auth.otp-login');
    }
    public function generate(Request $request)
    {
        $request->validate([
            'mobile_no'=>'required|exists:users,mobile_no'
        ]);
        $verificationotpcode = $this->generateOtp($request->mobile_no);
        // return $verificationotpcode;
        $message = 'Your OTP To Login is ' . $verificationotpcode->otp;
        return redirect()->route('otp.verification',['user_id'=>$verificationotpcode->user_id])->with('success',$message);

        // $message = 'Your OTP To Login is ' . $verificationotpcode->otp;

    }
    public function generateOtp($mobile_no)
    {
        $user = User::where('mobile_no',$mobile_no)->first();
        // return $user;
        $verificationotpcode = VerificationOtpCode::where('user_id',$user->id)->latest()->first();
        // return $verificationotpcode;
        $now = Carbon::now();
        if($verificationotpcode && $now->isBefore($verificationotpcode->expire_at)){
            return $verificationotpcode;
        }
        return VerificationOtpCode::create([
            'user_id'=>$user->id,
            'otp'=>rand('123456','999999'),
            'expire_at'=>Carbon::now()->addMinutes(10)
        ]);
    }
    public function verification($user_id)
    {
        return view('auth.otp-verification')->with(['user_id'=>$user_id]);
    }
    public function loginWithOtp(Request $request)
    {
        $request->validate([
            'user_id'=>'required|exists:users,id',
            'otp'=>'required'
        ]);
        $now = Carbon::now();
        $verificationcode = VerificationOtpCode::where('user_id',$request->user_id)->where('otp',$request->otp)->first();
        if(!$verificationcode){
            return redirect()->back()->with('error','Your OTP is not correct');
        }elseif($verificationcode && $now->isAfter($verificationcode->expire_at)){
            return redirect()->route('otp.login')->with('error','Your OTP has been expired');
        }
        $user = User::whereId($request->user_id)->first();
        $verificationcode->update([
            'expire_at'=>Carbon::now()
        ]);
        if($user){
            Auth::login($user);
            return redirect('/home');
        }
        return redirect()->route('otp.login')->with('error','Your OTP is not correct');
    }
}
