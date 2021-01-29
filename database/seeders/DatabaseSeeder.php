<?php

namespace Database\Seeders;

use App\Models\Receipt;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory()->create([
            'name' => 'Max',
            'email' => 'max@conradweberling.com',
            'password' => '$2y$10$tYzX7vNSUp7oml0n8q.aNuc6P3xxuJ.KOrLJbt7in2wpQm/CMmHYq'
        ]);

        User::factory(2)->create();

        Receipt::factory(500)->create();

        Cache::flush();

    }
}
