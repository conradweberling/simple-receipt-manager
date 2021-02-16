<?php

namespace Tests\Unit;

use App\Models\Receipt;
use PHPUnit\Framework\TestCase;

class CalcPaymentsTest extends TestCase
{

    public function testTwoEqualAmounts()
    {

        $this->assertEquals(
            [],
            Receipt::calcPayments(100, [50, 50], ['m', 'a'])
        );

    }

    public function testTwoDifferentAmounts()
    {

        $this->assertEquals(
            [
                [
                    'sender' => 'a',
                    'sum' => 10.0,
                    'recipient' => 'm'
                ]
            ],
            Receipt::calcPayments(100, [60, 40], ['m', 'a'])
        );

    }

    public function testThreeDifferentAmounts()
    {

        $this->assertEquals(
            [
                [
                    'sender' => 'b',
                    'sum' => 46.67,
                    'recipient' => 'm'
                ],
                [
                    'sender' => 'a',
                    'sum' => 6.67,
                    'recipient' => 'm'
                ]
            ],
            Receipt::calcPayments(200, [120, 60, 20], ['m', 'a', 'b'])
        );

    }




}
