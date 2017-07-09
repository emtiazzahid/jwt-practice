<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Namshi\JOSE\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try{
            $token = JWTAuth::attempt($credentials);

            if (!$token){
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        }
        catch(JWTException $e){
            return response()->json(['error' => 'something_went_wrong'], 500);
        }
        return response()->json(['token' => $token], 200);
    }

    public function register(Request $request)
    {
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ];
        $user = User::create($userData);
        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token], 200);
    }

    public function getUser()
    {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        return response()->json($user);
    }
}
