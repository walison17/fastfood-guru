<?php 

namespace App\Core\Auth;

interface Authenticable
{
    /**
     * pega o identificador único do usuário
     *
     * @return string
     */
    public function getId();

    /**
     * pega a senha do usuário
     *
     * @return string
     */
    public function getPassword();
}