<?php

namespace App\Core\Validation;

use Serializable, JsonSerializable;

class ErrorMessages implements Serializable, JsonSerializable
{
    /** @var string[] */
    private $messages;

    public function __construct(array $messages = [])
    {
        $this->messages = $messages;
    }

    /**
     * Verifica se existem mensagens para o campo 
     *
     * @param string $field
     */
    public function has(string $field)
    {
        return array_key_exists($field, $this->messages);
    }

    /**
     * Retorna a mensagem para o campo 
     *
     * @param string $field
     * @return string[]
     */
    public function get(string $field, bool $format = true)
    {
        return $format ? ucfirst(implode(', ', $this->messages[$field])) : $this->messages[$field];
    }

    /**
     * Retorna apenas a primeira mensagem para o campo
     *
     * @param string $field
     * @return string
     */
    public function getFirst(string $field)
    {
        return ucfirst($this->get($field, $format = false)[0]);
    }

    /**
     * Retorna todas as mensagens
     *
     * @return array
     */
    public function getAll()
    {
        return $this->messages;
    }

    /**
     * Serializa as mensagens para armazenamento
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->messages);
    }

    /**
     * Deserializa as mensagens armazenadas 
     *
     * @param array $messages
     * @return void
     */
    public function unserialize($messages) 
    {
        $this->messages = unserialize($messages);
    }

    public function jsonSerialize()
    {
        return $this->messages;
    }
}