<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Responses\ApiResponse;
use App\Http\Services\AuthService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private AuthService $authService;
    private UserService $userService;

    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    public function register(RegisterFormRequest $request): ApiResponse
    {
        $data = $request->validated();
        $response = $this->authService->register($data);
        return ApiResponse::create($response);
    }

    public function login(LoginFormRequest $request): ApiResponse
    {
        $data = $request->validated();
        $user = $this->userService->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return ApiResponse::__createBadResponse('Invalid Username and password');
        }

        $response = $this->authService->authResponse($user);
        return ApiResponse::create($response);
    }

    public function logout(Request $request): ApiResponse
    {
        $tokens = $request->user()->tokens()->get();
        foreach ($tokens as $token) {
            $token->revoke();
        }

        return ApiResponse::__create('Logged out');
    }
}
