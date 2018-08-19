<?php

namespace FakeApiClient\Model;


class User
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $name;
    /** @var string */
    protected $last_name;
    /** @var Address */
    protected $address;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getLast_name(): ?string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     * @return User
     */
    public function setLast_name(string $last_name): User
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     * @return User
     */
    public function setAddress(Address $address): User
    {
        $this->address = $address;

        return $this;
    }
}