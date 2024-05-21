<?php

namespace App\Http\Middlewares;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class BearerTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

     public function handle(Request $request, Closure $next)
     {
         $token = $request->bearerToken();
         if (!$token) {
             return response()->json(['message' => 'Token not provided'], 401);
         }
         try {
             $publicKey = base64_decode(env('JWT_PUBLIC_KEY')); // Ruta a la llave pública
             $decodedToken = JWT::decode($token, new Key($publicKey, 'RS256'));

             if(!isset($decodedToken->expires_in) || $decodedToken->expires_in < time()){
                 return response()->json(['message' => 'Token expired'], 401);
             }
             if(!isset($decodedToken->id) || !isset($decodedToken->email)){
                 return response()->json(['message' => 'Token invalid'], 401);
             }

             $user = User::where('id', $decodedToken->id)->where('email', $decodedToken->email)->first();
             if(!$user){
                 return response()->json(['message' => 'Token invalid'], 401);
             }

             session(['user' => $user]);

         } catch (\Exception $e) {
             return response()->json(['message' => $e->getMessage()], 401);
         }

         return $next($request);

     }
 }



