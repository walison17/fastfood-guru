<?php 

namespace App\Core\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class Confirmed extends AbstractRule
{
    public $compareTo;

    public function __construct($compareTo)
    {
        $this->compareTo = $compareTo;
    }   

    public function validate($input)
    {
        return $input === $this->compareTo; 
    }
}