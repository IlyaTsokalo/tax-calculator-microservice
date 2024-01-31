<?php

namespace App\Service\TaxCalculation;

use App\Service\Strategy\TaxCalculatorStrategyInterface;
use App\Shared\DTO\TaxCollectionTransfer;
use App\Shared\DTO\TaxRequestTransfer;

interface TaxCalculationServiceInterface
{
    /**
     * @param \App\Shared\DTO\TaxRequestTransfer $taxRequestTransfer
     * @param \App\Service\Strategy\TaxCalculatorStrategyInterface $taxCalculatorStrategy
     *
     * @return \App\Shared\DTO\TaxCollectionTransfer
     */
    public function calculateTax(TaxRequestTransfer $taxRequestTransfer, TaxCalculatorStrategyInterface $taxCalculatorStrategy): TaxCollectionTransfer;
}
