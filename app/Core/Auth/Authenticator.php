<?php 

namespace App\Core\Auth;

use App\Core\Auth\Authenticable;
use App\Support\StorageInterface;
use App\Core\Auth\AuthRepositoryInterface;
use App\Core\Auth\Exceptions\UserDoesntExistsException;
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
     * @param string $email
     * @param string $password
     * @throws \App\Core\Auth\Exceptions\UserDoesntExistsException
     * @throws \App\Core\Auth\Exceptions\PasswordDontMatchException
     * @return Authenticable
     */
    public function authenticate(string $email, string $password) : Authenticable
    {
        /** @var AuthenticableInterface */
        $user = $this->repository->getByEmail($email);

        if (! $user) {
            throw new UserDoesntExistsException;
        }

        if (! password_verify($password, $user->getPassword())) {
            throw new PasswordDontMatchException;
        }

        $this->storage->set(self::STORAGE_KEY, $user->getId());

        return $user;
    }

    /**
     * Retorna o usuário ativo 
     *
     * @return Authenticable|null
     */
    public function getCurrentUser()
    {
        if (! $this->check()) return null;
        
        $id = $this->storage->get(self::STORAGE_KEY); 

        return $this->repository->getById($id);
    }

    /**
     * Verifica se o usuário está logado/ativo
     *
     * @return boolean
     */
    public function check() : bool
    {
        return $this->storage->exists(self::STORAGE_KEY);
    }

    /**
     * Faz login do usuário
     * 
     * deve ser usado apenas para testes ou quando se tem certeza absoluta que o usuário 
     * já possui cadastro
     *
     * @param Authenticable $user
     * @return void
     */
    public function loginAs(Authenticable $user) : void 
    {
        $this->storage->set(self::STORAGE_KEY, $user->getId());
    }

    /**
     * Faz logout/desativa o usuário
     *
     * @return void
     */
    public function logout() : void
    {
        $this->storage->remove(self::STORAGE_KEY);
    }
}