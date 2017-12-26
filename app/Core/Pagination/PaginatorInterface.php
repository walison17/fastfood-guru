<?php

namespace App\Core\Pagination;

interface PaginatorInterface
{
    public function getTotalItems();

    public function getTotalPages();

    public function getPerPage();

    public function getPage(int $number);
}