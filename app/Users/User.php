<?php 

namespace App\Users;

use App\Core\Entity;
use App\Core\Auth\Authenticable;

class User extends Entity implements Authenticable
{
    private $email;
    private $name;
    private $cep;
    private $password;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function setCep($cep)
    {
        $this->cep = $cep; 

        return $this;
    }

    public function getCep()
    {
        return $this->cep;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function toArray()
    {
        return [
            'email' => $this->email,
            'name' => $this->name, 
            'password' => $this->password,
            'cep' => $this->cep
        ];
    }
}