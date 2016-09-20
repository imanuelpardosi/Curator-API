<?php
namespace App\Http\Controllers\Auth;

use App\Repositories\AccessTokenRepository;
use Illuminate\Routing\ResponseFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $responseFactory;
    private $access_token;
    private $auth;

    public function __construct(ResponseFactory $responseFactory, AccessTokenRepository $access_token_repository, Guard $auth)
    {
        $this->responseFactory = $responseFactory;
        $this->access_token = $access_token_repository;
        $this->auth=$auth;
    }

    public function login(Request $request)
    { 
        $credentials = $request->only('email', 'password');
        //var_dump($this->auth->attempt($credentials));
        //echo Hash::make("imanuel123");

        if (\Auth::attempt($credentials)) {

            $token = bin2hex(openssl_random_pseudo_bytes(16));

            $token_result = $this->access_token->generateToken($token);

            if (!$token_result) {
                return response()->json(['error' => 'Error while generate token'], 500);

            }

            $response = [
                'admin_id' => $token_result->admin_id,
                'access_token' => $token_result->access_token,
                'id_token' => $token_result->id
            ];
            return response()->json($response, 200);

        } else {
            return response()->json(['error' => 'Login failed'], 401);

        }
    }

}
