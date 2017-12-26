<?php

namespace App\Controllers\Auth;

use App\Users\User;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Core\Validation\Validator;
use Respect\Validation\Validator as v;
use App\Users\UsersRepositoryInterface;

class RegistrationController
{
    /** @var UsersRepositoryInterface */
    private $repository;

    public function __construct(UsersRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function showForm(Request $request, Response $response)
    {   
        return view('auth/subscribe-form');
    }

    public function register(Request $request, Response $response, $args)
    {
        $validator = new Validator;

        $input = [
            'email' => $request->getParam('email'),
            'name' => $request->getParam('nome'),
            'cep' => $request->getParam('cep'),
            'password' => $request->getParam('senha'),
            'password_confirmation' => $request->getParam('senha_confirmacao')
        ]; 

        $rules = [
            'email' => v::notOptional()->email()->emailAvailable(),
            'name' => v::notOptional(),
            'cep' => v::notOptional(),
            'password' => v::notOptional()
                ->alnum()
                ->noWhitespace()
                ->confirmed($request->getParam('senha_confirmacao')),
        ];

        $validator->validate($input, $rules);

        if ($validator->fail()) {
            flash_errors($validator->getMessages());
            
            return redirect('reg.showForm');
        }

        $user = new User;
        $user->setEmail($request->getParam('email'));
        $user->setName($request->getParam('nome'));
        $user->setCep($request->getParam('cep'));
        $user->setPassword(password_hash($request->getParam('senha'), PASSWORD_BCRYPT)) ;

        $this->repository->save($user);

        flash('success', 'Cadastro concluído');

        return redirect('home');
    }
}