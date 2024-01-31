<?php

namespace App\Service\Strategy\Selector;

use App\Service\Strategy\TaxCalculatorStrategyInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TaxCalculatorStrategySelector implements TaxCalculatorStrategySelectorInterface
{
    /**
     * @param TaxCalculatorStrategyInterface[] $strategies
     */
    public function __construct(protected array $strategies)
    {
    }

    /**
     * @param string $countryCode
     *
     * @return \App\Service\Strategy\TaxCalculatorStrategyInterface
     */
    public function selectStrategy(string $countryCode): TaxCalculatorStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if (in_array($countryCode, $strategy->getApplicableCountries())) {
                return $strategy;
            }
        }

        throw new UnprocessableEntityHttpException("No applicable strategy found for country: {$countryCode}", null, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
