<?php 

namespace App\Core;

abstract class Entity
{
    protected $id;
    
    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
}