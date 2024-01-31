<?php

namespace App\Service\Strategy;

use App\Shared\DTO\TaxCollectionTransfer;
use App\Shared\DTO\TaxRequestTransfer;

interface TaxCalculatorStrategyInterface
{
    /**
     * @param \App\Shared\DTO\TaxRequestTransfer $taxRequestTransfer
     *
     * @return \App\Shared\DTO\TaxCollectionTransfer
     */
    public function calculateTax(TaxRequestTransfer $taxRequestTransfer): TaxCollectionTransfer;

    /**
     * @return string[]
     */
    public function getApplicableCountries(): array;
}
