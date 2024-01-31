<?php

namespace App\Service\Cache;

use App\Shared\DTO\TaxRequestTransfer;

class TaxCacheKeyGenerator implements TaxCacheKeyGeneratorInterface
{
    protected const CACHE_TIME_TO_LIVE = 300;
    public function generateCacheKey(TaxRequestTransfer $taxRequestTransfer): string
    {
        return strtolower(sprintf('taxes_%s_%s', $taxRequestTransfer->getCountry(), $taxRequestTransfer->getState()));
    }

    public function getCacheTimeToLive(): int
    {
        return self::CACHE_TIME_TO_LIVE;
    }
}
