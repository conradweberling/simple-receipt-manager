<?php

namespace Database\Seeders;

use App\Models\Receipt;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class TestSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::addDemoUser();
        User::factory()->create(['name' => 'Noah', 'color' => '#545ed5']);
        User::factory()->create(['name' => 'James', 'color' => '#418699']);

        foreach ($this->getTestReceipts() as $row) {

            $count = $row['count'];
            unset($row['count']);

            for($i=0;$i<$count;$i++) {

                Receipt::factory()->create(array_merge($row, ['date' => now()]));
                Receipt::factory()->create(array_merge($row, ['date' => now()->subMonth()]));
                Receipt::factory()->create(array_merge($row, ['date' => now()->subMonths(2)]));

            }


        }

        Cache::flush();
    }

    /**
     * @return \int[][]
     */
    public function getTestReceipts() {

        return [
            [
                'user_id' => 1,
                'count' => 10,
                'amount' => 10,
            ],
            [
                'user_id' => 2,
                'count' => 6,
                'amount' => 5,
            ],
            [
                'user_id' => 3,
                'count' => 8,
                'amount' => 15,
            ]
        ];

    }
}
