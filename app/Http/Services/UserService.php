<?php

namespace App\Http\Services;

use App\Models\User;

class UserService
{
    public function findByEmail(string $email): ?User
    {
        return User::query()->whereEmail($email)->first();
    }
}
