<?php 

namespace App\Auth;


interface AuthInterface
{
    /**
     * pega o identificador único do usuário
     *
     * @return string
     */
    public function getAuthIdentifier();

    /**
     * pega o nome do identificador único do usuário 
     *
     * @return void
     */
    public function getAuthIdentifierName();

    /**
     * pega a senha do usuário
     *
     * @return string
     */
    public function getAuthPassword();
}