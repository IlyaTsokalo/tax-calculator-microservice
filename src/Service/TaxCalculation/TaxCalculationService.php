<?php

namespace App\Service\TaxCalculation;

use App\Service\Cache\TaxCacheKeyGeneratorInterface;
use App\Service\Strategy\TaxCalculatorStrategyInterface;
use App\Shared\DTO\TaxCollectionTransfer;
use App\Shared\DTO\TaxRequestTransfer;
use Symfony\Contracts\Cache\CacheInterface;

class TaxCalculationService implements TaxCalculationServiceInterface
{
    public function __construct(
        protected CacheInterface                $cache,
        protected TaxCacheKeyGeneratorInterface $taxCacheKeyGenerator,
    )
    {
    }

    /**
     * @param \App\Shared\DTO\TaxRequestTransfer $taxRequestTransfer
     * @param \App\Service\Strategy\TaxCalculatorStrategyInterface $taxCalculatorStrategy
     *
     * @return \App\Shared\DTO\TaxCollectionTransfer
     */
    public function calculateTax(TaxRequestTransfer $taxRequestTransfer, TaxCalculatorStrategyInterface $taxCalculatorStrategy): TaxCollectionTransfer
    {
        $cacheKey = $this->taxCacheKeyGenerator->generateCacheKey($taxRequestTransfer);

        $taxCollectionSerialized = $this->cache->get($cacheKey, function ($cacheItem) use ($taxCalculatorStrategy, $taxRequestTransfer) {
            $cacheItem->expiresAfter($this->taxCacheKeyGenerator->getCacheTimeToLive());
            return json_encode($taxCalculatorStrategy->calculateTax($taxRequestTransfer)->toArray());
        });

        return (new TaxCollectionTransfer())->fromArray(json_decode($taxCollectionSerialized, true));
    }
}

