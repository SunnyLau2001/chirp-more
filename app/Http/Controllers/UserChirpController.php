<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserChirpController extends Controller{
  public function user_chirps(Request $request): Response
  {
    $result = User::with('chirps.user', 'chirps.chirplikes.user:id,name', 'chirplikes.chirp.user', 'chirplikes.chirp.chirplikes.user:id,name')->find($request->user()->id, ['id']);

    return Inertia::render("MyChirps/Index", [
        'chirps' => $result->chirps,
        'chirplikes' => $result->chirplikes,
    ]);
  }

}