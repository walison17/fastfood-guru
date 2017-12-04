<?php 

namespace App\Controllers\Auth;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Auth\Authenticator;
use App\Services\SubscribeUser;

class SubscriptionController
{
    /** @var \App\Auth\Authenticator */
    private $authenticator;

    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function index(Request $request, Response $response, $args)
    {
        return view('auth/index');
    }

    public function subscribe(Request $request, Response $response, $args)
    {
        $user = SubscribeUser::fromRequest($request);

        $this->authenticator->activate($user);

        return redirect('home');
    }
}