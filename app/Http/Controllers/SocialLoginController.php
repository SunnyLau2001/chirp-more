<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * Social login redirect. Driver specified by route parameters.
     */
    public function redirect(string $driver): RedirectResponse
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback(Request $request, string $driver)
    {
        switch ($driver)
        {
            case "github": return $this->handle_github_login($request);
        }
        
    }

    public function handle_github_login(Request $request)
    {
        
        if (is_null($request->query('code')) || is_null($request->query('state')))
        {
            // Unauthorized
            return redirect(route('login'), 401);
        }
        
        try {
            $github_user = Socialite::driver('github')->user();
        } catch (Exception $exception) {
            return redirect(route('login'), 401);
        }

        if ($user = User::where('github_id', $github_user->id)->first()) {
            // If founded
            Auth::login($user);
        } else if ($user = User::where('email', $github_user->email)->first()) {
            // If not founded but having same email
            DB::transaction(function() use ($user, $github_user) {
                $user->github_id = $github_user->id;
                $user->save();
            });
            Auth::login($user);
        } else {
            // Register new user
            $user = DB::transaction(function() use ($github_user) {
                return User::create([
                    'name' => $github_user->name,
                    'email' => $github_user->email,
                    'github_id' => $github_user->id,
                    'password' => Hash::make(uniqid('Gh_'))
                ]);
            });
            event(new Registered($user));
            Auth::login($user);
        }

        return redirect('/');
    }
}
