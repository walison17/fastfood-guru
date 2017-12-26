<?php 

namespace App\Core\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{
    public function validate($input)
    {
        return $input 
            ? is_null(app('UsersRepository')->getByEmail($input))
            : true ;
    }
}