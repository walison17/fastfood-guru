<?php

namespace App\Controllers\Auth;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Domain\User\User;
use App\Domain\User\Location;
use App\Core\Validation\Validator;
use Respect\Validation\Validator as v;
use App\Domain\User\UsersRepositoryInterface;

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

        $rules = [
            'email' => v::notOptional()->email()->emailAvailable(),
            'cep' => v::notOptional(),
            'name' => v::notOptional(),
            'password' => v::notOptional()
                ->alnum()
                ->noWhitespace()
                ->confirmed($request->getParam('password_confirmation')),
        ];

        $validator->validate($request->getParams(), $rules);

        if ($validator->fail()) {
            flash_errors($validator->getMessages());
            
            return redirect('reg.showForm');
        }

        $user = new User;
        $user->setName($request->getParam('name'));
        $location = new Location(
            $request->getParam('city'),
            $request->getParam('state'),
            $request->getParam('lat'),
            $request->getParam('lng')
        );
        $user->setLocation($location);
        $user->setEmail($request->getParam('email'));
        $user->setPassword(password_hash($request->getParam('password'), PASSWORD_BCRYPT)) ;

        $this->repository->save($user);

        flash('success', 'cadastro concluído :)');

        return view('auth.showForm');
    }
}