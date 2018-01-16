<?php 

namespace App\Controllers\Auth;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Core\Auth\Authenticator;
use App\Core\Validation\Validator;
use Respect\Validation\Validator as v;
use App\Core\Auth\Exceptions\UserDoesntExistsException;
use App\Core\Auth\Exceptions\PasswordDontMatchException;

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
    public function showForm(Request $request, Response $response, $args)
    {
        return view('auth/login-form');
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
        $validator = new Validator;

        $validator->validate(
            [
                'email' => $request->getParam('email'),
                'password' => $request->getParam('password')
            ],
            [
                'email' => v::notOptional()->email(),
                'password' => v::notOptional()
            ]
        );

        if ($validator->fail()) {
            flash_errors($validator->getMessages());

            return redirect('auth.showForm');
        }

        try {
            $this->authenticator->authenticate(
                [
                    'email' => $request->getParam('email'), 
                    'password' => $request->getParam('password')
                ]
            );
        } catch (UserDoesntExistsException $ex) {
            flash_error('email','Email não cadastrado');

            return redirect('auth.showForm');
        } catch (PasswordDontMatchException $ex) {
            flash_error('password', 'Senha incorreta');

            return redirect('auth.showForm');
        }

        flash('success', 'bem-vindo ' . auth()->getCurrentUser()->getName());

        return redirect_back();
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
