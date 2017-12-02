<?php 

namespace App\Auth\Providers;

interface AuthProviderInterface 
{
    /**
     * busca o usuário através do id
     *
     * @param string $identifier
     * @return \App\Auth\AuthInterface
     */
    public function retrieveByIdentifier(string $identifier);

    /**
     * busca o usuário através das credenciais informadas
     *
     * @param array $credentials
     * @return \App\Auth\AuthInterface
     */
    public function retrieveByCredentials(array $credentials);
}