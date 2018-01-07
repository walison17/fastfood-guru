<?php

namespace App\Domain\User;

class Location
{
    private $city;
    private $state;
    private $latitude;
    private $longitude;

    public function __construct(string $city, string $state, float $latitude, float $longitude)
    {
        $this->city = $city;
        $this->state = $state;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }
}