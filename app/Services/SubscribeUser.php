<?php 

namespace App\Services;

use App\Models\User;
use Slim\Http\Request;

class SubscribeUser
{   
    private $username;
    private $password;
    private $email;
    private $photo;

    public function __construct(string $username, string $password, string $email, string $photo = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->photo = $photo;
    }

    public static function fromRequest(Request $request)
    {
        if (! self::validate($request)) {
            // exception
        }

        $subscription = new static(
            $request->getParam('username'),
            $request->getParam('password'),
            $request->getParam('email'),
            $request->getParam('photo')
        );

        $user = $subscription->create();
    }

    public function create()
    {
        return User::create([
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'photo' => $this->photo
        ]);
    }
}