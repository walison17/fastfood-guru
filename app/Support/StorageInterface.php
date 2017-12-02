<?php

namespace App\Support;

/**
 * interface para armazenamento 
 * 
 * @author Walison Filipe <walisonfilipe@hotmail.com>
 */
interface StorageInterface 
{   
    /**
     * recupera um item armazenado
     *
     * @param mixed $index
     * 
     * @return mixed
     */
    public function get($index);

    /**
     * armazena um item 
     *
     * @param mixed $index
     * @param mixed $value
     * 
     * @return void
     */
    public function set($index, $value);

    /**
     * recupera todos itens armazenados
     *
     * @return mixed[]
     */
    public function all();

    /**
     * verifica se um determinado item está armazenado
     *
     * @param mixed $index
     * 
     * @return boolean
     */
    public function exists($index);

    /**
     * remove um item
     *
     * @param mixed $index
     * 
     * @return void
     */
    public function remove($index);

    /**
     * limpa todos os itens armazenados
     *
     * @return void
     */
    public function clear();
}