<?php

namespace App\Http\Controllers;

use App\Models\UserFollowing;
use Illuminate\Http\Request;

class UserFollowingController extends Controller
{
    //
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'following_id' => 'required|integer',
        ]);

        $id = (int) (strval($validated['user_id']) . strval($validated['following_id']));

        $user_following = UserFollowing::create([
            'id' => $id,
            ...$validated
        ]);

        return response()->json([
            'status' => 'success',
            'user_following' => $user_following
        ], 201);
    }

    public function destroy(UserFollowing $userfollowing) {
        dd($userfollowing);
    }
}
