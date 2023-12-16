<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect ($driver) {
        return Socialite::driver($driver)->stateless()->redirect();
    }

    public function callback ($driver) {
        try {
            $socialUser = Socialite::driver($driver)->stateless()->user();

            // We're using driver or provider in the tables
            $user = User::where('driverID', $socialUser->getId())->first();
            
            if (!$user) {
                $newUser = User::create([
                    'names' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'driverID' => $socialUser->getId(),
                    'driver' => $driver,
                ]);

                // Because we're using stateless, reconsider
                Auth::login($newUser);

                return redirect()->intended('/onboarding/choose-domain-n-email');
            } elseif ($user) {
                Auth::login($user);

                return redirect()->intended('dashboard');
            }
        } catch (\Throwable $th) {
            //throw $th;
            dd('Something went wrong!!!'. $th);
        }
    }
}
