<?php

namespace App\Tests\Entity;

use App\Entity\Forecast;
use PHPUnit\Framework\TestCase;

class ForecastTest extends TestCase
{
    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],
            ['100', 212],
            ['-100', -148],
            ['0.5', 32.9],
            ['-0.5', 31.1],
            ['15.8', 60.44],
            ['-16.5', 2.3],
            ['77.2', 170.96],
            ['100.1', 212.18 ],
            ['-100.1', -148.18],
        ];
    }

    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $forecast = new Forecast();

//        $forecast->setTemperature(0);
//        $this->assertEquals(32, $forecast->getFahrenheit());
//
//        $forecast->setTemperature(-100);
//        $this->assertEquals(-148, $forecast->getFahrenheit());
//
//        $forecast->setTemperature(100);
//        $this->assertEquals(212, $forecast->getFahrenheit());

        $forecast->setTemperature($celsius);
        $actualFahrenheit = $forecast->getFahrenheit();

        $this->assertEquals($expectedFahrenheit, $actualFahrenheit, "Expected $expectedFahrenheit fahrenheit for $celsius Celsius, got $actualFahrenheit");
    }
}
