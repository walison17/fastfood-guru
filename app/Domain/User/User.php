<?php 

namespace App\Domain\User;

use App\Domain\Entity;
use App\Core\Auth\Authenticable;

class User extends Entity implements Authenticable
{
    private $email;
    private $name;
    private $cep;
    private $password;
    private $location;
    private $photo;
    private $username;

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

    public function setCep(?string $cep)
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

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation(Location $location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Retorna a foto de usuário
     *
     * @return \App\Domain\User\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto(Photo $photo)
    {
        $this->photo = $photo;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }
}