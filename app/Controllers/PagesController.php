<?php 

namespace App\Controllers;


use Slim\Http\Request;
use Slim\Http\Response;


class PagesController{

    function index(Request $request,Response $response)
    {
        return view("home");    
    }

    function empresas(Request $request,Response $response)
    {
        return view('empresas');
    }

    function empresa(Request $request,Response $response,$args)
    {
        if (isset($args['empresa']) && $args['empresa'] != "") {
            return view('perfil-empresa');
        }else{
            return 'Erro';
        }
       
    }

    function avaliacao(Request $request,Response $response, $args)
    {
        return view('avaliacao');
    }

    function profile(Request $request,Response $response)
    {
        if (auth()->check()){
            return view("user/profile");
        }else{
            return redirect("home");
        }    
    }

}