<?php

namespace Database\Factories;

use App\Models\Receipt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Receipt::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'image' => "images/dummy1000x1500.png",
            'thumbnail' => "images/dummy500x850.png",
            'amount' => $this->faker->randomFloat(2, 1, 100),
            'date' => $this->faker->dateTimeBetween('-6 month', 'now')
        ];
    }
}
