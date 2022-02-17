<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;

class UserController extends Controller
{
    public function profile(): ApiResponse
    {
        return ApiResponse::create(
            new UserResource(auth()->user())
        );
    }
}
