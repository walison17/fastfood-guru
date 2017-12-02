<?php 

namespace App\Controllers\Auth;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Auth\Authenticator;

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
        die('login.index');
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

    }
}
