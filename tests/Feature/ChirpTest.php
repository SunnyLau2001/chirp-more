<?php

namespace Tests\Feature;

use App\Models\Chirp;
use App\Models\ChirpLike;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChirpTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_create_chirps_and_users(): void
    {
        $users = User::factory()->has(Chirp::factory()->count(3))->count(3)->create();   

        foreach ($users as $user) {
            $this->assertDatabaseHas('users', [
                'id' => $user->id
            ]);

            foreach ($user->chirps as $chirp) {
                $this->assertDatabaseHas('chirps', [
                    'id' => $chirp->id,
                    'user_id' => $user->id,
                ]);
            }
        }
        
        
        $chirp_likes = ChirpLike::factory()->count(3)->create();
        echo "{$chirp_likes} \n";

        // $id = Chirp::inRandomOrder()->first()->id;
        // echo "$id";

        // $response = $this->get('/chirps');
        // $response->assertOk();
    }
}
