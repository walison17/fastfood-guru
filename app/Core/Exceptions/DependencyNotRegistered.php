<?php 

namespace App\Core\Exceptions;

class DependencyNotRegistered extends \Exception
{ 
   public function __construct($dependency)
    {
        parent::__construct("Depêndencia $dependency não registrada no container", 500);
    }
}