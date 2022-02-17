<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (User::ROLE['ADMIN'] != auth()->user()->role) {
            throw new NotFoundHttpException();
        }
        return $next($request);
    }
}
