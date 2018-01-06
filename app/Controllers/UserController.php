<?php 

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Core\Validation\Validator;
use Respect\Validation\Validator as v;

class UserController
{
    public function update(Request $request, Response $response, $args)
    {
        $validator = new Validator;

        $rules = [
            'nome' => v::notOptional(),
            'senha' => v::notOptional()->confirmed($request->getParam('repita-senha'))
        ];

        $validator->validate($request->getParams(), $rules);

        if ($validator->fail()) {
            return json($validator->getMessages(), 400);
        }

        return json('perfil atualizado!');
    }
}