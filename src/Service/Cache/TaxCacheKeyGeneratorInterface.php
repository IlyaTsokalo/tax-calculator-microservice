<?php

namespace App\Service\Cache;

use App\Shared\DTO\TaxRequestTransfer;

interface TaxCacheKeyGeneratorInterface
{
    public function generateCacheKey(TaxRequestTransfer $taxRequestTransfer): string;

    public function getCacheTimeToLive(): int;
}
