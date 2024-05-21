<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    //

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $token =  $this->token(Auth::user());
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => time() + env('JWT_EXPIRATION_TIME', 1800)
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request){
        $user = User::find(session('auth_user')->id);

        $token = $this->token($user);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => time() + env('JWT_EXPIRATION_TIME', 1800)
        ]);
    }

    public function token($user)
    {
        $privateKey = base64_decode(env('JWT_PRIVATE_KEY')); // Ruta a la llave privada
        $payload = [
            'id' => strval($user->id) , // Debe ser único para cada usuario
            'email' => $user->email,
            'expires_in' => time() + env('JWT_EXPIRATION_TIME', 1800)
        ];

        $token = JWT::encode($payload, $privateKey, 'RS256');
        return $token;
    }

}
