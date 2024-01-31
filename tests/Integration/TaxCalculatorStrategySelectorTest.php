<?php

namespace App\Tests\Integration;

use App\Service\Strategy\Selector\TaxCalculatorStrategySelectorInterface;
use App\Service\Strategy\SeriousTaxCalculatorStrategy;
use App\Service\Strategy\TaxBeeCalculatorStrategy;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxCalculatorStrategySelectorTest extends KernelTestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param array<string> $countries
     * @param string $expectedStrategy
     *
     * @throws \Exception
     * @return void
     */
    public function testSelectStrategy(array $countries, string $expectedStrategy): void
    {
        $strategySelector = $this->getContainer()->get(TaxCalculatorStrategySelectorInterface::class);


        foreach ($countries as $country) {
            $strategy = $strategySelector->selectStrategy($country);
            $this->assertInstanceOf($expectedStrategy, $strategy);
        }
    }

    /**
     * @return array<array<mixed, mixed>>
     */
    public static function dataProvider(): array
    {
        return [
            [
                'countries' => ['US', 'CA'],
                'expectedStrategy' => TaxBeeCalculatorStrategy::class,
            ],
            [
                'countries' => ['LT', 'LV', 'EE', 'PL'],
                'expectedStrategy' => SeriousTaxCalculatorStrategy::class,
            ],
        ];
    }
}
