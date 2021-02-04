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
            'email' => 'max@example.net',
            'password' => '$2y$10$17oQb9TW1gNaHqawehrjWu5gGYsz7JsgxmW8qHLmhLKEXimg0So46'
        ]);

        User::factory(2)->create();

        Receipt::factory(500)->create();

        Cache::flush();

    }
}
