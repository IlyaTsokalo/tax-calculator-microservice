<?php

namespace App\Service\Strategy;

class SeriousTaxCalculatorStrategy extends AbstractTaxCalculatorStrategy
{
    public const APPLICABLE_COUNTRIES = ['LT', 'LV', 'EE', 'PL', 'DE'];

    /**
     * @return string[]
     */
    public function getApplicableCountries(): array
    {
        return self::APPLICABLE_COUNTRIES;
    }
}
