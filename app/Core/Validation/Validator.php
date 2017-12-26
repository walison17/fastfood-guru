<?php 

namespace App\Core\Validation;

use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    private $errors = []; 

    /**
     * Executa a validação dos campos
     *
     * @return $this
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

        return $this;
    }

    /**
     * Verifica se a validação falhou
     *
     * @return bool 
     */
    public function fail()
    {
        return count($this->errors) > 0;
    }

    /**
     * Retorna todas as mensagens de erro
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->errors;
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