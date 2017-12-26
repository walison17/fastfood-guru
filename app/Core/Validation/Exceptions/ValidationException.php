<?php

namespace App\Core\Validation\Exceptions;

class ValidationException extends \Exception
{
    private $messages;

    public function __construct(array $messages)
    {
        $this->messages = $messages;

        parent::__construct();
    }

    public function getErrorMessages()
    {
        return $this->messages;
    }
}