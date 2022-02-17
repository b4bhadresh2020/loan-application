<?php

namespace App\Http\Services;

use App\Exceptions\BadHttpResponseException;
use App\Http\Resources\UserResource;
use App\Models\User;

class AuthService
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(array $data): array
    {
        $user = User::create([
            'email' => $data['email'],
            'password' => $data['password'],
            'name' => $data['name'],
            'email_verified_at' => now(),
        ]);

        return $this->authResponse($user);
    }

    public function authResponse(User $user)
    {
        $authToken = $user->createToken('authToken');
        $token = $authToken->token;

        return [
            'user' => new UserResource($user),
            'access_token' => $authToken->accessToken,
            'expires_at' => $token->expires_at
        ];
    }
}
