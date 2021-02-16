<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');
        $this->seed(TestSeeder::class);

        $this->user = User::find(1);
    }

}
