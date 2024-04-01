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

        $result = UserFollowing::findOr($id, function () use ($id, $validated) {
            return UserFollowing::create([
                'id' => $id,
                ...$validated
            ]);
        });

        return response()->json([
            'status' => 'success',
            'user_following' => $result
        ], 201);
    }

    public function destroy(UserFollowing $userfollowing) {
        $result = $userfollowing->delete();
        
        return response()->json([
            'status' => $result ? "success" : "failed",
            'deleted' => $result,
        ], 200);
    }

    public function get_user_followings(String $user_id)
    {
        $user_id = (int) $user_id;
        $result = UserFollowing::with('following:id,name')->where('user_id', $user_id)->get();
        return response()->json($result);
    }
}
