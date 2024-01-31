<?php

namespace App\Service\Strategy\Selector;

use App\Service\Strategy\TaxCalculatorStrategyInterface;

interface TaxCalculatorStrategySelectorInterface
{
    /**
     * @param string $countryCode
     *
     * @return \App\Service\Strategy\TaxCalculatorStrategyInterface
     */
    public function selectStrategy(string $countryCode): TaxCalculatorStrategyInterface;
}
