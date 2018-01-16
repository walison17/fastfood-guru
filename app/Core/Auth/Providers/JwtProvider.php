<?php 

namespace App\Core\Auth\Providers;

use Firebase\JWT\JWT;
use App\Core\Auth\Authenticatable;
use Psr\Http\Message\RequestInterface;
use App\Support\StorageInterface as Storage;
use App\Core\Auth\AuthRepositoryInterface as AuthRepository;

/**
 * @author Walison Filipe <walisonfilipe@hotmail.com>
 */
class JwtProvider implements UserProviderInterface
{
    /**
     * Repositório utilizado para comunicação com banco de dados 
     *
     * @var AuthRepository
     */
    private $repository;

    /**
     * Requisição
     *
     * @var RequestInterface
     */
    private $request;

    public function __construct(AuthRepository $repository, RequestInterface $request)
    {
        $this->repository = $repository;
        $this->request = $request;
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
        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return Authenticatable|null
     */
    public function getActivated()
    {
        [$token] = $this->request->getHeader('Authorization');
        $decoded = JWT::decode($token, config('jwt_key'), ['HS256']);

        return $this->repository->getById($decoded->id);
     }

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function deactivate()
    {
        return null;
    }
}