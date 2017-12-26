<?php

namespace App\Core\Pagination;

class Paginator implements PaginatorInterface
{
    private $items;
    private $total; 
    private $perPage;

    public function __construct(array $items, int $total, int $perPage = 20)
    {
        $this->items = $items;
        $this->total = $total;
        $this->perPage = $perPage;
    }

    /**
     * Rertorna a página para o número informado
     *
     * @param integer $number
     * @return \App\Core\Pagination\PageInterface
     */
    public function getPage(int $number)
    {
        return new Page($this->items, $number, $this);
    }

    /**
     * Retorna o total de itens à serem páginados
     *
     * @return int
     */
    public function getTotalItems()
    {
        return $this->total;
    }

    /**
     * Retorna o total de páginas
     *
     * @return int
     */
    public function getTotalPages()
    {
        return (int) ceil($this->total / $this->perPage);
    }

    /**
     * Retorna a quantidade de itens exibidos por página 
     *
     * @param integer $number
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }
}