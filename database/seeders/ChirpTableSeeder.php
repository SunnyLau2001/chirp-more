<?php

namespace Database\Seeders;

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChirpTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Used to disable the broadcasting event passed.
        Chirp::unsetEventDispatcher();

        //
        $users = User::all();

        foreach ($users as $user) {
            Chirp::factory()->count(4)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
