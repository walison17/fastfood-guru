<?php 

namespace App\Core\Auth;

use App\Core\Auth\Authenticable;
use App\Support\StorageInterface;
use App\Core\Auth\AuthRepositoryInterface;
use App\Core\Auth\Exceptions\PasswordDontMatchException;

class Authenticator
{
    /** @var \App\Support\StorageInterface */
    protected $storage;

    /** @var \App\Core\Auth\AuthRepositoryInterface */
    protected $repository;

    private const STORAGE_KEY = 'user';

    public function __construct(AuthRepositoryInterface $repository, StorageInterface $storage)
    {
        $this->storage = $storage;
        $this->repository = $repository;
    }

    /**
     * Tenta autenticar o usuário através das credenciais informadas 
     *
     * @param array $credentials
     * @return void
     */
    public function authenticate(string $email, string $password)
    {
        /** @var AuthenticableInterface */
        $user = $this->repository->getByEmail($email);

        if (! password_verify($password, $user->getPassword())) {
            throw new PasswordDontMatchException;
        }

        $this->storage->set(self::STORAGE_KEY, $user->getId());

        return $user;
    }

    /**
     * Retorna o usuário ativo 
     *
     * @return AuthenticableInterface|null
     */
    public function getCurrentUser()
    {
        if (! $this->isAuthenticated()) {
            return null;
        }
        
        $id = $this->storage->get(self::STORAGE_KEY); 

        return $this->repository->getById($id);
    }

    /**
     * Verifica se o usuário está logado/ativo
     *
     * @return boolean
     */
    public function isAuthenticated() 
    {
        return $this->storage->exists(self::STORAGE_KEY);
    }

    /**
     * Faz logout/desativa o usuário
     *
     * @return void
     */
    public function logout()
    {
        $this->storage->remove(self::STORAGE_KEY);
    }
}