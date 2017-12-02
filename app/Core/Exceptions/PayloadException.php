<?php

namespace App\Core\Exceptions;


class PayloadException extends \Exception 
{
    public function __construct($message)
    {
        parent::__construct($message, 400);
    }
}