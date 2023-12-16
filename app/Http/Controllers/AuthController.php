<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Services\Auth\AuthService;

class AuthController extends Controller
{
    public function pre_login(Request $request, AuthService $authService)
    {
        $user = $authService->pre_login($request);
        return response($user);
    }

    public function login(Request $request, AuthService $authService)
    {
        $user = $authService->onLogin($request);
        return response($user);
    }

    public function signup(Request $request, AuthService $authService)
    {
        $user = $authService->signup($request);
        return response()->json($user, $user['status']);
    }

    public function savenSendOtp(Request $request, AuthService $authService)
    {
        $instance = $authService->savenSendOtp($request);
        return response()->json($instance, $instance['status']);
    }
    

    // public function changePassword (Request $request, AuthService $authService) {
    //     $service = $authService->changePassword($request);
    //     return response($service);
    // }

    // public function check_password (AuthService $authService) {
    //     $service = $authService->check_password();
    //     return response($service);
    // }

    // public function tenantlogin(Request $request, AuthService $authService)
    // {
    //     $user = $authService->tenantlogin($request);
    //     return response($user);
    // }

    // public function register(Request $request, AuthService $authService){
    //     $user = $authService->register($request);
    //     return response($user);
    // }
    
    // public function verifyEmailForWebsiteEdit(Request $request, AuthService $authService){
    //     $verifierRes = $authService->verifyEmailForWebsiteEdit($request);
    //     return response($verifierRes);
    // }


    // public function verifyEmailForRegistration(Request $request, AuthService $authService){
    //     $verifierRes = $authService->verifyEmailForRegistration($request);
    //     return response($verifierRes);
    // }

    // public function sendOtpForUserResetPassword(Request $request, AuthService $authService){
    //     $sendOtpres = $authService->sendOtpForUserResetPassword($request);
    //     return response($sendOtpres);
    // }

    // public function resetPassword(Request $request, AuthService $authService){
    //     $passResetRes = $authService->resetPassword($request);
    //     return response($passResetRes);
    // }

    public function verifyOtp(Request $request, AuthService $authService) {
        $verifier = $authService->verifyOtp($request);
        return response()->json($verifier);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['status' => 401], 200);
    }
}
