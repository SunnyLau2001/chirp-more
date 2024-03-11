<?php

namespace Tests\Feature;

use App\Models\User;
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
        $user = User::factory()->make();   

        print($user);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
