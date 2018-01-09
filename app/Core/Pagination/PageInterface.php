<?php

namespace App\Core\Pagination;

interface PageInterface
{
    public function hasNext();

    public function next();

    public function hasPrevious();

    public function previous();

    public function results();

    public function lastIndex();
}
