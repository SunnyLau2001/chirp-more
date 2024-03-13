<?php

namespace Tests\Feature;

use App\Models\Chirp;
use App\Models\ChirpLike;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
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
        
        // Duplicate like for a chirp from the same user may occur
        $chirp_likes = ChirpLike::factory()->count(3)->create();
        echo "{$chirp_likes} \n";

        // $id = Chirp::inRandomOrder()->first()->id;
        // echo "$id";

        // $response = $this->get('/chirps');
        // $response->assertOk();
    }

    public function test_show_chirps(): void
    {
        $users = User::factory()->has(Chirp::factory()->count(3))->count(3)->create();

        // Disable if not running the vite dev server, otherwise error occur
        $this->withoutVite();

        foreach ($users as $user)
        {
            $response = $this->actingAs($user)->get(route('chirps.index'));

            $response->assertInertia(fn (AssertableInertia $page) => $page
                // check chirps property exist
                ->has('chirps', 9, fn (AssertableInertia $page) => $page
                    ->has('id')
                    ->has('user_id')
                    ->has('user')
                    ->has('message')
                    ->has('chirplikes')
                    ->etc()
                )
            );

            // Check for user's chirps are shown
            $response->assertSee($user->name);
        }
    }
}
