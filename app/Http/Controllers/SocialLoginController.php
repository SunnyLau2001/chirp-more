<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    //
    public function redirect(string $driver)
    {
        dd($driver);
        return Socialite::driver('github')->redirect();
    }

}
