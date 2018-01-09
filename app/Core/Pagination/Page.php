<?php

namespace App\Core\Pagination;

class Page implements PageInterface
{
    /**
     * número da página 
     *
     * @var int
     */
    private $number;

    /**
     * itens paginados 
     *
     * @var array
     */
    private $results;

    /**
     * Paginador
     *
     * @var \App\Core\Pagination\PaginatorInterface
     */
    private $paginator;

    public function __construct(array $results, int $number, PaginatorInterface $paginator)
    {
        $this->results = $results;
        $this->number = $number;
        $this->paginator = $paginator;
    }

    /**
     * Retorna o número da próxima página
     *
     * @return int|null
     */
    public function next()
    {
        return $this->hasNext() ? $this->number + 1 : null;
    }
    
    /**
     * Verifica se possui próxima página 
     *
     * @return boolean
     */
    public function hasNext()
    {
        return ($this->number + 1) * $this->paginator->getPerPage() 
            <= $this->paginator->getTotalItems();
    }

    /**
     * Retorna o número da página anterior 
     *
     * @return int|null
     */
    public function previous()
    {
        return $this->hasPrevious() ? $this->number - 1 : null;
    }
    
    /**
     * Verifica se possuí página anterior 
     *
     * @return boolean
     */
    public function hasPrevious()
    {
        return $this->number - 1 > 0;
    }

    /**
     * Retorna todos os itens páginados 
     *
     * @return array
     */
    public function results()
    {
        return $this->results;
    }

    /**
     * Retorna o número da última página
     *
     * @return int
     */
    public function lastIndex()
    {
        return $this->paginator->getTotalPages();
    }

    public function __toString()
    {
        return $this->number;
    }
}