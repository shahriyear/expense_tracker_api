<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends Controller
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create a new token.
     * 
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user)
    {
        $payload = [
            'iss' => "expense-tracker",
            'sub' => $user->id,
            'name' => $user->name,
            'iat' => time(),
            'exp' => time() + 24 * 60 * 60,
        ];
        $payload['token'] = JWT::encode($payload, env('JWT_SECRET'));
        return $payload;
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     * 
     * @param  \App\User   $user 
     * @return mixed
     */
    public function authenticate(User $user)
    {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            return response400('Email does not exist.');
        }

        if (Hash::check($this->request->input('password'), $user->password)) {
            return jwtResponse($this->jwt($user));
        }

        return response400('Email or password is wrong.');
    }
}
