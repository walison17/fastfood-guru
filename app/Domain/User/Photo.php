<?php

namespace App\Domain\User;

class Photo
{
    private $filename;

    public function __construct(?string $filename)
    {
        $this->filename = $filename;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function __toString()
    {
        return $this->filename;
    }
}