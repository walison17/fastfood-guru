<?php 

namespace App\Auth;

use App\Auth\AuthProvider;
use App\Auth\AuthInterface;
use App\Support\StorageInterface;
use \App\Auth\Providers\AuthProviderInterface;

class Authenticator
{
    /** @var StorageInterface */
    protected $storage;

    /** @var AuthProviderInterface */
    protected $provider; 

    private const STORAGE_KEY = 'user';

    public function __construct(AuthProviderInterface $provider, StorageInterface $storage)
    {
        $this->storage = $storage;
        $this->provider = $provider;
    }

    /**
     * tenta autenticar o usuário através de suas credenciais
     *
     * @param array $credentials
     * @return void
     */
    public function authenticate(array $credentials)
    {
        /** @var \App\Auth\AuthInterface */
        $user = $this->provider->retrieveByCredentials($credentials);

        $this->activate($user);

        return $user;
    }

    /**
     * pega o usuário ativo
     *
     * @return \App\Auth\AuthInterface
     */
    public function user()
    {
        if (! $this->check()) {
            return null;
        }
        
        $identifier = $this->storage->get(self::STORAGE_KEY); 

        return $this->provider->retrieveByIdentifier($identifier);
    }

    /**
     * adiciona o usuário na sessão 
     *
     * @param \App\Auth\AuthInterface $user
     * @return void
     */
    public function activate(AuthInterface $user)
    {
        $this->storage->set(self::STORAGE_KEY, $user->getAuthIdentifier());
    }

    /**
     * verifica se o usuário está logado/ativo
     *
     * @return boolean
     */
    public function check() 
    {
        return $this->storage->exists(self::STORAGE_KEY);
    }

    /**
     * faz logout/desativa o usuário
     *
     * @return void
     */
    public function logout()
    {
        $this->storage->remove(self::STORAGE_KEY);
    }
}