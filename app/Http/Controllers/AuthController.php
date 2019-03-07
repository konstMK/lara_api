<?php

namespace App\Http\Controllers;


use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if(!$request->has('username') &&
            !$request->has('password'))
        {
            return response()->json(
                [
                    'error' => 'Username and password not specified'
                ]
            , Response::HTTP_UNAUTHORIZED);
        }
        $username = $request->get('username');
        $password = $request->get('password');

        $user = User::where('username', '=', $username)->first();
        if (!$user) {
            return response()->json(
                [
                    'error' => 'Username not found'
                ]
            , Response::HTTP_UNAUTHORIZED);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json(
                [
                    'error' => 'Invalid password'
                ]
                , Response::HTTP_FORBIDDEN);
        }

        return response()->json(
            [
                'token' => $this->jwt($user)
            ]
        );

    }

    public function loginApikey(Request $request)
    {
        if(!$request->has('apikey'))
        {
            return response()->json(
                [
                    'error' => 'API KEY not specified'
                ]
            , Response::HTTP_UNAUTHORIZED);
        }
        $apikey = $request->get('apikey');

        $user = User::where('apikey', '=', $apikey)->first();
        if (!$user) {
            return response()->json(
                [
                    'error' => 'User not found'
                ]
            , Response::HTTP_UNAUTHORIZED);
        }

        return response()->json(
            [
                'token' => $this->jwt($user)
            ]
        );

    }

    public function register(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $user = User::where('username', '=', $username)->first();
        if ($user) {
            return response()->json([
                'error' => 'User already exists'
            ],Response::HTTP_CONFLICT);
        }

        $apikey = md5(uniqid());
        User::create([
            'username' => $username,
            'password' => Hash::make($password),
            'apikey' => $apikey
        ]);

        return response(null, Response::HTTP_CREATED);
    }

    private function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60*60 // Expiration time
        ];
        
        return JWT::encode($payload, env('JWT_SECRET'));
    }
}