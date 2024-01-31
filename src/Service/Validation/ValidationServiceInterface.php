<?php

namespace App\Service\Validation;

use Symfony\Component\HttpFoundation\Request;

interface ValidationServiceInterface
{
    public function validateTaxRequest(Request $request, string $taxCalculatorStrategyClassName): void;

    public function validateCountry(Request $request): void;
}
