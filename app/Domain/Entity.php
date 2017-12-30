<?php 

namespace App\Domain;

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

        return $this;
    }

    public function equals(Entity $other)
    {
        return $this->id == $other->getId();
    }
}