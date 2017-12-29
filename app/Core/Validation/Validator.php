<?php 

namespace App\Core\Validation;

use App\Core\Validation\ErrorMessages;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    private $errors = []; 

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function validate(array $input, array $rules, array $customMessages = [])
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($input[$field]);
            } catch(NestedValidationException $e) {
                $e->findMessages($this->loadTranslations());
                $this->errors[$field] = $e->getMessages();
            }
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return bool 
     */
    public function fail()
    {
        return count($this->errors) > 0;
    }

    /**
     * {@inheritDoc}
     *
     * @return \App\Core\Validation\ErrorMessages
     */
    public function getMessages()
    {
        return new ErrorMessages($this->errors);
    }

    /**
     * Carrega as mensagens traduzidas 
     *
     * @return string[]
     */
    private function loadTranslations()
    {
        $translatedMessages = require __DIR__ . '/messages.php';

        return $translatedMessages;
    }   
}