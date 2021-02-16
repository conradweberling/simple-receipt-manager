<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardTest extends TestCase
{

    /**
     * Test dashboard view
     */
    public function testDashboardView()
    {

        $this->actingAs($this->user)->get(route('home'))
            ->assertStatus(200);

    }

    /**
     * Test chart json
     */
    public function testChartJson()
    {

        $this->actingAs($this->user)->get(route('dashboard.chart'))
            ->assertStatus(200)
            ->assertJsonFragment(['total' => 3]) //month count
            ->assertJsonFragment(['total' => 250]) //total month amount
            ->assertJsonFragment(['amounts' => [30, 100, 120]]) //user amounts
            ->assertJsonFragment(['colors' => ['#39e262', '#545ed5', '#418699']])
            ->assertJsonFragment(['names' => ['James', 'Max', 'Noah']])
            ->assertJsonFragment(['payments' => [
                ['recipient' => 'James','sender' => 'Noah','sum' => 36.67],
                ['recipient' => 'Max','sender' => 'Noah','sum' => 16.66]
            ]]);

    }
}
