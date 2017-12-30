<?php 

namespace App\Controllers;


use Slim\Http\Request;
use Slim\Http\Response;


class PagesController{

    function index(Request $request,Response $response){
        return view("home");    
    }

    function profile(Request $request,Response $response){
        if (auth()->check()){
            return view("user/profile");
        }else{
            return redirect("home");
        }    
    }

}