<?php

namespace App\Service\Strategy;

class TaxBeeCalculatorStrategy extends AbstractTaxCalculatorStrategy
{
    public const APPLICABLE_COUNTRIES = ['US', 'CA'];

    /**
     * @return string[]
     */
    public function getApplicableCountries(): array
    {
        return self::APPLICABLE_COUNTRIES;
    }
}
