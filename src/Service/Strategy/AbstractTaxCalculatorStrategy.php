<?php

namespace App\Service\Strategy;

use App\Service\Adapter\TaxAdapterInterface;
use App\Service\CircuitBreaker\CircuitBreaker;
use App\Shared\DTO\TaxCollectionTransfer;
use App\Shared\DTO\TaxRequestTransfer;

abstract class AbstractTaxCalculatorStrategy implements TaxCalculatorStrategyInterface
{
    public function __construct(protected TaxAdapterInterface $taxAdapter, protected CircuitBreaker $circuitBreaker)
    {
    }

    /**
     * @param \App\Shared\DTO\TaxRequestTransfer $taxRequestTransfer
     *
     * @return \App\Shared\DTO\TaxCollectionTransfer
     */
    public function calculateTax(TaxRequestTransfer $taxRequestTransfer): TaxCollectionTransfer
    {
        return $this->circuitBreaker->callService(
            fn() => $this->taxAdapter->calculateTax($taxRequestTransfer)
        );
    }

    /**
     * @return string[]
     */
    abstract public function getApplicableCountries(): array;
}
