<?php

namespace App\Core\Pagination;

interface PaginatorInterface
{
    public function getTotalItems();

    public function getTotalPages();

    public function getPageSize();

    public function getPage(int $number);

    public function getRange(int $currentPage, int $size = 5);
}