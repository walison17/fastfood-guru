<?php 

namespace App\Core\Exceptions;

/**
 * Exceção lançada quando o token do site estiver inválido
 */
class InvalidTokenException extends \Exception 
{
    public function __construct()
    {
        parent::__construct('Token inválido', 401);
    }
}