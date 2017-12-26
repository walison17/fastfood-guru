<?php 

namespace App\Core\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class ConfirmedException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'as senhas devem ser iguais',
        ],
    ];
}