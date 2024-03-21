<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class PreviewController extends Controller
{
    /*
     * Return welcome page for guest user with certain data.
     */
    public function welcome(): InertiaResponse
    {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'chirps' => Chirp::with('user:id,name', 'likes.user:id,name')->latest()->take(10)->get(),
            'userCounts' => User::count(),
            'chirpCounts' => Chirp::count(),
        ]);
    }

    public function get_chirp_count(): JsonResponse
    {
        $count = Chirp::count();
        return response()->json([
            'count' => $count
        ]);
    }

    public function get_user_count(): JsonResponse
    {
        $count = User::count();
        return response()->json([
            'count' => $count
        ]);
    }
}
