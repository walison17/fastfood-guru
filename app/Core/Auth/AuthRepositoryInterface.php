<?php

namespace App\Core\Auth;

interface AuthRepositoryInterface
{
    /**
     * Busca um usuário a partir de seu identificador
     *
     * @param mixed $id
     * @return \App\Core\Auth\Authenticable|null
     */
    public function getById($id);

    /**
     * Busca um usuário pelo email  
     *
     * @param string $email
     * @return \App\Core\Auth\Authenticable|null
     */
    public function getByEmail(string $email);
}