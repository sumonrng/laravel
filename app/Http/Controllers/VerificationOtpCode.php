<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerificationOtpCode extends Controller
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

    }
}
