<?php

use App\Http\Controllers\ChirpController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\UserChirpController;
use App\Http\Controllers\UserFollowingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Social login credential login
Route::get('/auth/redirect/{driver}', [SocialLoginController::class, 'redirect'])->name('auth.redirect');
 
Route::get('/auth/callback/{driver}', [SocialLoginController::class, 'callback']);


Route::get('/', [PreviewController::class, 'welcome']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::Resource('chirps', ChirpController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);
    
Route::resource('likes', LikeController::class)
    ->only(['index', 'store', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::get('/mychirps', [UserChirpController::class, 'user_chirps'])->middleware(['auth', 'verified'])->name('mychirps');

Route::middleware(['auth', 'verified'])->group(function() {
    Route::post('/userfollowing', [UserFollowingController::class, 'store'])->name('userfollowing.store');
    Route::delete('/userfollowing/{userfollowing}', [UserFollowingController::class, 'destroy'])->name('userfollowing.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
