<?php

namespace FakeApiClient\Model;

class Address
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $country;
    /** @var string */
    protected $iso_code;
    /** @var string */
    protected $city;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Address
     */
    public function setId(int $id): Address
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Address
     */
    public function setCountry(string $country): Address
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getIso_code(): ?string
    {
        return $this->iso_code;
    }

    /**
     * @param string $iso_code
     * @return Address
     */
    public function setIso_code(string $iso_code): Address
    {
        $this->iso_code = $iso_code;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Address
     */
    public function setCity(string $city): Address
    {
        $this->city = $city;

        return $this;
    }
}