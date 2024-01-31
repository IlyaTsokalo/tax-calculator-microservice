<?php

namespace App\Service\Mapper;

use App\ExternalService\TaxBee\TaxResult;
use App\Shared\DTO\TaxTransfer;

class TaxTransferMapper
{
    /**
     * @param \App\ExternalService\TaxBee\TaxResult $taxResult
     *
     * @return \App\Shared\DTO\TaxTransfer
     */
    public function mapTaxResultToTaxTransfer(TaxResult $taxResult): TaxTransfer
    {
        return (new TaxTransfer())
        ->setPercentage($taxResult->taxPercentage)
        ->setTaxType($taxResult->type->value);
    }
}
