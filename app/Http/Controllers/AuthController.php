<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Services\UserService;

class AuthController extends Controller
{
    private $_service;
    public function __construct(UserService $service)
    {
        $this->_service = $service;
    }

    public function register(Request $request)
    {
        $data = $request->only(['name', 'email', 'password']);
        $result = $this->_service->registerUser($data);

        return response()->noContent();
    }

    public function login(Request $request) {
        $data = $request->only(['email', 'password']);
        $token = $this->_service->login($data);

        if (!$token) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        return response([
            "status" => "success",
            "authorisation" => [
                'token' => $token,
                'type' => "bearer"
            ]
        ], 200);


        /*return response([
            "status" => "success"
        ], 200)->withCookie(
            'token',
            $token,
            config('jwt.ttl'),
            '/',
            null,
            true,
            true,
            false,
            "None"
        );*/
    }

}
