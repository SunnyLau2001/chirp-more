<?php

namespace App\Http\Controllers;

use App\Models\ChirpLike;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChirpLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
        return response(200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        // Seems validate will return response when invalid
        $validated = $request->validate([
            "chirp_id" => "required",
            "user_id" => "required",
        ]);

        // $data = [
        //     "chirp_id" => $request->post('chirp_id'),
        //     "user_id" => $request->post('user_id'),
        //     "like" => true,
        // ];

        $like = ChirpLike::create([
            ...$validated,
            "like" => true,
        ]);
        
        // dd($validated);
        

        return response(json_encode($like), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(ChirpLike $chirpLike)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChirpLike $chirpLike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChirpLike $chirpLike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChirpLike $chirplike): Response
    {
        $this->authorize('delete', $chirplike);
        
        $result = $chirplike->delete();

        return response(json_encode(['result' => $result]), 200);
    }
}
