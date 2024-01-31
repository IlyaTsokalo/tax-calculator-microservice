<?php

namespace App\Shared\DTO;

class TaxTransfer
{
    protected string $taxType;
    protected float $percentage;

    public function getTaxType(): string
    {
        return $this->taxType;
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }

    public function setTaxType(string $taxType): static
    {
        $this->taxType = $taxType;

        return $this;
    }

    public function setPercentage(float $percentage): static
    {
        $this->percentage = $percentage;

        return $this;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'taxType' => $this->taxType,
            'percentage' => $this->percentage,
        ];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return $this
     */
    public function fromArray(array $data): static
    {
        $this->taxType = $data['taxType'];
        $this->percentage = $data['percentage'];

        return $this;
    }
}
