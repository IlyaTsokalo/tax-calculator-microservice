<?php

namespace App\Service\Adapter;

use App\ExternalService\TaxBee\TaxBee;
use App\Service\Mapper\TaxTransferMapper;
use App\Shared\DTO\TaxCollectionTransfer;
use App\Shared\DTO\TaxRequestTransfer;

class TaxBeeAdapter implements TaxAdapterInterface
{
    public function __construct(protected TaxBee $taxBee, protected TaxTransferMapper $taxTransferMapper)
    {

    }

    /**
     * @param \App\Shared\DTO\TaxRequestTransfer $taxRequestTransfer
     *
     * @return \App\Shared\DTO\TaxCollectionTransfer
     */
    public function calculateTax(TaxRequestTransfer $taxRequestTransfer): TaxCollectionTransfer
    {
        $taxCollectionTransfer = new TaxCollectionTransfer();

        $taxes = $this->taxBee->getTaxes(
            $taxRequestTransfer->getCountry(),
            $taxRequestTransfer->getState(),
            $taxRequestTransfer->getCity(),
            $taxRequestTransfer->getStreet(),
            $taxRequestTransfer->getPostcode()
        );

        foreach ($taxes as $taxResult) {
            $taxCollectionTransfer->addTax($this->taxTransferMapper->mapTaxResultToTaxTransfer($taxResult));
        }

        return $taxCollectionTransfer;
    }
}
