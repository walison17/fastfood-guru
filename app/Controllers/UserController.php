<?php 

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Core\Validation\Validator;
use Respect\Validation\Validator as v;
use App\Domain\User\UsersRepositoryInterface;

class UserController
{
    private $repository;

    public function __construct(UsersRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function update(Request $request, Response $response, $args)
    {
        $validator = new Validator;

        $rules = [
            'nome' => v::notOptional(),
            'senha_atual' => v::notOptional(),
            'senha' => v::confirmed($request->getParam('repita-senha'))
        ];
        
        $validator->validate($request->getParams(), $rules);

        if ($validator->fail()) {
            return json($validator->getMessages(), 400);
        }

        $user = auth()->getCurrentUser(); 
        
        if (!password_verify($request->getParam('senha_atual'), $user->getPassword())) {
            return json(['senha_atual' => 'Senha incorreta'], 400);
        }
        
        $user->setName($request->getParam('nome'));
        if($request->getParam('senha') != ""){
            $user->setPassword(password_hash($request->getParam('senha'), PASSWORD_BCRYPT)) ;
        }
        
        $this->repository->save($user);

        return json('perfil atualizado!');
    }
}