<?php

namespace App\Core\Auth\Providers;

use App\Core\Auth\Authenticatable;

/**
 * @author Walison Filipe <walisonfilipe@hotmail.com>
 */
interface UserProviderInterface
{
    /**
     * Busca o usuário 
     *
     * @param array $credentials
     * @return Authenticatable|null
     */
    public function find(array $credentials);

    /**
     * Verifica se as credenciais informadas são válidas
     *
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials);
    
    /**
     * Retorna o usuário atual/ativo
     *
     * @return Authenticatable
     */
    public function getActivated();

    /**
     * Ativa o usuário 
     * ex.: Insere dados do usuário na sessão
     *
     * @param Authenticatable $user 
     * @return void
     */
    public function activate(Authenticatable $user);

    /**
     * Desativa o usuário
     * ex.: Remove dados do usuário da sessão
     *
     * @return void
     */
    public function deactivate();
}