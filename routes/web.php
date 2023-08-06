<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthOtpController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/otp/login',[AuthOtpController::class,'login'])->name('otp.login');
Route::post('/otp/generate',[AuthOtpController::class,'generate'])->name('otp.generate');
Route::get('/otp/verification/{user_id}',[AuthOtpController::class,'verification'])->name('otp.verification');
Route::post('/otp/login',[AuthOtpController::class,'loginWithOtp'])->name('otp.getlogin');
