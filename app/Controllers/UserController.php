<?php 

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Domain\User\Location;
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

    /**
     * Atualiza os dados do usuário
     *
     * @param Request $request
     * @param Response $response
     * @param mixed $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(Request $request, Response $response, $args)
    {
        $validator = new Validator;

        $validator->validate($request->getParams(), [
            'nome' => v::notOptional(),
            'senha_atual' => v::notOptional(),
            'senha' => v::confirmed($request->getParam('repita-senha'))
        ]);

        if ($validator->fail()) {
            return json($validator->getMessages(), 400);
        }

        $user = auth()->getCurrentUser(); 
        
        if (! password_verify($request->getParam('senha_atual'), $user->getPassword())) {
            return json($validator->getMessages()->add('senha_atual', 'senha incorreta'), 400);
        }

        if(!empty($request->getParam('city'))){
            $location = new Location(
                $request->getParam('city'),
                $request->getParam('state'),
                (float) $request->getParam('lat'),
                (float) $request->getParam('lng')
            );
            $user->setLocation($location);
        }
    
        $user->setName($request->getParam('nome'));
        if(! empty($request->getParam('senha'))){
            $user->setPassword(password_hash($request->getParam('senha'), PASSWORD_BCRYPT));
        }
        
        $this->repository->save($user);

        return json('perfil atualizado!');
    }
}