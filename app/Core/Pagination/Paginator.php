<?php

namespace App\Core\Pagination;

class Paginator implements PaginatorInterface
{
    private $items;
    private $total; 
    private $pageSize;

    public function __construct(array $items, int $total, int $pageSize = 20)
    {
        $this->items = $items;
        $this->total = $total;
        $this->pageSize = $pageSize;
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

    public function getRange(int $currentPage, int $size = 5)
    {
        $begin = max($currentPage - $size, 1); 
        $end = min($currentPage + $size, $this->getTotalPages());

        return range($begin, $end);
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
        return (int) ceil($this->total / $this->pageSize);
    }

    /**
     * Retorna a quantidade de itens exibidos por página, ou seja, o tamanho da página  
     *
     * @param integer $number
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }
}