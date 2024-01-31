<?php

namespace App\Shared\DTO;

use App\Service\Strategy\SeriousTaxCalculatorStrategy;
use App\Service\Strategy\TaxBeeCalculatorStrategy;
use Symfony\Component\Validator\Constraints as Assert;

class TaxRequestTransfer
{
    #[Assert\NotBlank(groups: [TaxBeeCalculatorStrategy::class, SeriousTaxCalculatorStrategy::class])]
    protected string $country;
    #[Assert\NotBlank(groups: [TaxBeeCalculatorStrategy::class, SeriousTaxCalculatorStrategy::class])]
    protected string $state;
    #[Assert\NotBlank(groups: [TaxBeeCalculatorStrategy::class])]
    protected string $city;
    #[Assert\NotBlank(groups: [TaxBeeCalculatorStrategy::class])]
    protected string $street;
    #[Assert\NotBlank(groups: [TaxBeeCalculatorStrategy::class])]
    protected string $postcode;

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setCountry(string $country): static
    {
        $this->country = strtoupper($country);

        return $this;
    }

    public function setState(string $state): static
    {
        $this->state = strtolower($state);

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function setPostcode(string $postcode): static
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * @param string[] $data
     *
     * @return $this
     */
    public function fromArray(array $data): static
    {
        foreach ($data as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }
            if ($key === 'country') {
                $value = strtoupper($value);
            }
            if ($key === 'state') {
                $value = strtolower($value);
            }
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'street' => $this->street,
            'postcode' => $this->postcode,
        ];
    }
}
