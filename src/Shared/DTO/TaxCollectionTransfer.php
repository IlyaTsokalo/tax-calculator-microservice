<?php

namespace App\Shared\DTO;

use JsonSerializable;

class TaxCollectionTransfer implements JsonSerializable
{
    /**
     * @var \App\Shared\DTO\TaxTransfer[] $taxes
     */
    protected array $taxes = [];

    /**
     * @param \App\Shared\DTO\TaxTransfer $taxTransfer
     *
     * @return $this
     */
    public function addTax(TaxTransfer $taxTransfer): static
    {
        $this->taxes[] = $taxTransfer;

        return $this;
    }

    /**
     * @return \App\Shared\DTO\TaxTransfer[]
     */
    public function getTaxes(): array
    {
        return $this->taxes;
    }

    /**
     * @param \App\Shared\DTO\TaxTransfer[] $taxes
     *
     * @return $this
     */
    public function setTaxes(array $taxes): static
    {
        $this->taxes = $taxes;

        return $this;
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function toArray(): array
    {
        $taxes = [];
        foreach ($this->taxes as $tax) {
            $taxes[] = $tax->toArray();
        }

        return $taxes;
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @param array<array<string, mixed>> $taxes
     *
     * @return $this
     */
    public function fromArray(array $taxes): static
    {
        foreach ($taxes as $tax) {
            $this->addTax((new TaxTransfer())->fromArray($tax));
        }

        return $this;
    }
}
