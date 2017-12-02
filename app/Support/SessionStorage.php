<?php

namespace App\Support;

use Countable; 

/**
 * classe responsável por armazenar dados na sessão
 * 
 * @author Walison Filipe <walisonfilipe@hotmail.com>
 */
class SessionStorage implements StorageInterface, Countable
{
    /** @var string nome do repositório */
    private $bucket;

    public function __construct(string $bucket = 'default')
    {   
        if (! isset($_SESSION[$bucket])) {
            $_SESSION[$bucket] = [];
        } 

        $this->bucket = $bucket;
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $index
     * @return void
     */
    public function get($index)
    {
        if (! $this->exists($index)) {
            return null;
        }

        return $_SESSION[$this->bucket][$index];
    }

    /**
     * {@inheritDoc} 
     *
     * @param mixed $index
     * @param mixed $value
     * @return void
     */
    public function set($index, $value)
    {
        $_SESSION[$this->bucket][$index] = $value;
    }

    /**
     * {@inheritDoc} 
     *
     * @param mixed $index
     * @return void
     */
    public function remove($index)
    {
        if ($this->exists($index)) {
            unset($_SESSION[$this->bucket][$index]);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $index
     * @return boolean
     */
    public function exists($index)
    {
        return isset($_SESSION[$this->bucket][$index]);
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function all()
    {
        return $_SESSION[$this->bucket];
    }

    /**
     * {@inheritDoc}
     *
     * @return integer
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function clear()
    {
        unset($_SESSION[$this->bucket]);
    }
}