<?php 

namespace App\Controllers\Auth;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Core\AuthAuth\Authenticator;

class AuthController 
{
    /** @var Authenticator */
    private $authenticator;

    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    /**
     * retorna o formulário de login 
     *
     * @param Request $request
     * @param Response $response
     * @param mixed $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(Request $request, Response $response, $args)
    {
    
    }
    /**
     * faz login do usuário
     *
     * @param Request $request
     * @param Response $response
     * @param mixed $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login(Request $request, Response $response, $args)
    {
        $this->authenticator->authenticate($request->getParam('username'), $request->getParam('password'));

        return \redirect('home');
    }

    /**
     * faz logout do usuário
     *
     * @param Request $request
     * @param Response $response
     * @param mixed $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function logout(Request $request, Response $response, $args)
    {
        $this->authenticator->logout();

        return \redirect('home');
    }
}
