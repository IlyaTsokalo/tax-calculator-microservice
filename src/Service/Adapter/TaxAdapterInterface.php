<?php

namespace App\Service\Adapter;

use App\Shared\DTO\TaxCollectionTransfer;
use App\Shared\DTO\TaxRequestTransfer;

interface TaxAdapterInterface
{
    /**
     * @param \App\Shared\DTO\TaxRequestTransfer $taxRequestTransfer
     *
     * @return \App\Shared\DTO\TaxCollectionTransfer
     */
    public function calculateTax(TaxRequestTransfer $taxRequestTransfer): TaxCollectionTransfer;
}
