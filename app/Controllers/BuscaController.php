<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class BuscaController
{
    function index(Request $request,Response $response)
    {
        $query = $request->getQueryParam('query');
        return view("busca",[
            'query' => $query
        ]);    
    }
}