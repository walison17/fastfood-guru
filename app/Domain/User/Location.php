<?php

namespace App\Domain\User;

class Location
{
    private $city;
    private $state;
    private $postalCode; 

    public function __construct(int $postalCode, string $city, string $state)
    {
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->state = $state;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function getState()
    {
        return $this->state;
    }
}