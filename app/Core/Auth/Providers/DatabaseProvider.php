<?php 

namespace App\Core\Auth\Providers;

use App\Core\Auth\Authenticatable;
use App\Support\StorageInterface as Storage;
use App\Core\Auth\AuthRepositoryInterface as AuthRepository;

/**
 * @author Walison Filipe <walisonfilipe@hotmail.com>
 */
class DatabaseProvider implements UserProviderInterface
{
    /**
     * Chave de armazenamento
     */
    const STORAGE_KEY = 'auth';

    /**
     * Repositório utilizado para comunicação com banco de dados 
     *
     * @var AuthRepository
     */
    private $repository;

    /**
     * Armazenamento utilizado para guardar dados pertecentes ao usuário logado 
     *
     * @var Storage
     */
    private $storage;

    public function __construct(AuthRepository $repository, Storage $storage)
    {
        $this->repository = $repository;
        $this->storage = $storage;
    }

    /**
     * {@inheritDoc}
     *
     * @param array $credentials
     * @return Authenticatable|null
     */
    public function find(array $credentials)
    {
        return $this->repository->getByEmail($credentials['email']);
    }

    /**
     * {@inheritDoc}
     *
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return password_verify($credentials['password'], $user->getPassword());        
    }

    /**
     * {@inheritDoc}
     *
     * @param Authenticatable $user
     * @return void
     */
    public function activate(Authenticatable $user)
    {
        $this->storage->set(self::STORAGE_KEY, $user->getId());
    }

    /**
     * {@inheritDoc}
     *
     * @return Authenticatable|null
     */
    public function getActivated()
    {
        return $this->repository->getById($this->storage->get(self::STORAGE_KEY));
    }

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function deactivate()
    {
        $this->storage->remove(self::STORAGE_KEY);
    }
}