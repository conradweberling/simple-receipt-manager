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

        User::addDemoUser();
        User::factory(2)->create();

        Receipt::factory(500)->create();

        Cache::flush();

    }
}
