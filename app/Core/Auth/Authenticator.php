<?php 

namespace App\Core\Auth;

use App\Core\Auth\Authenticatable;
use App\Core\Auth\Providers\UserProviderInterface as UserProvider;
use App\Core\Auth\Exceptions\UserDoesntExistsException;
use App\Core\Auth\Exceptions\PasswordDontMatchException;

class Authenticator
{
    /** 
     * @var UserProvider
     */
    private $provider;

    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Tenta autenticar o usuário através das credenciais informadas 
     * 
     * @param array $credentials
     * @throws UserDoesntExistsException
     * @throws PasswordDontMatchException
     * @return Authenticatable
     */
    public function authenticate(array $credentials) : Authenticatable
    {
        $user = $this->provider->find($credentials);

        if (! $user) {
            throw new UserDoesntExistsException;
        }

        if (! $this->provider->validateCredentials($user, $credentials)) {
            throw new PasswordDontMatchException;
        }

        $this->provider->activate($user);

        return $user;
    }

    /**
     * Retorna o usuário ativo 
     *
     * @return Authenticatable|null
     */
    public function getCurrentUser()
    {
        return $this->provider->getActivated();
    }

    /**
     * Verifica se o usuário está logado/ativo
     *
     * @return boolean
     */
    public function check() : bool
    {
        return ! is_null($this->provider->getActivated());
    }

    /**
     * Faz login do usuário
     * 
     * deve ser usado apenas para testes ou quando se tem certeza absoluta que o usuário 
     * já possui cadastro
     *
     * @param Authenticatable $user
     * @return void
     */
    public function loginAs(Authenticatable $user) : void 
    {
        $this->provider->activate($user);
    }

    /**
     * Faz logout/desativa o usuário
     *
     * @return void
     */
    public function logout() : void
    {
        $this->provider->deactivate();
    }
}