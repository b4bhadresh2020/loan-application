<?php

namespace App\Exceptions;

use App\Http\Responses\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if (strpos(url()->current(), '/api/v1/')) {
            if ($exception instanceof AuthenticationException) {
                Log::error($exception);
                return ApiResponse::__createUnAuthorizedResponse($exception->getMessage());
            } else if ($exception instanceof NotFoundHttpException) {
                Log::error($exception);
                return ApiResponse::create(["message" => ["Page Not Found"]], false, ApiResponse::NOT_FOUND);
            } else if ($exception instanceof MethodNotAllowedException) {
                Log::error($exception);
                return ApiResponse::__createServerError("invalid api method");
            } else if ($exception instanceof MethodNotAllowedHttpException) {
                Log::error($exception);
                return ApiResponse::__createServerError("invalid api method");
            } else if ($exception instanceof ModelNotFoundException) {
                Log::error($exception);
                $model = explode('\\', $exception->getModel());
                $modelPhrase = ucwords(implode(' ', preg_split('/(?=[A-Z])/', end($model))));
                $msg = \App::make($exception->getModel())->modelNotFoundMessage ?? "$modelPhrase not found";
                return ApiResponse::__createServerError(trim($msg));
            } else if ($exception instanceof BadHttpResponseException) {
                Log::error($exception);
                return ApiResponse::__createBadResponse($exception->getMessage());
            }
            Log::error($exception);
            return ApiResponse::createServerError($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $currentUrl = url()->current();
        $pos = strpos($currentUrl, '/api/v1/');

        if ($request->expectsJson() or !($pos === false)) {
            return ApiResponse::create(['message' => ["Unauthorized user."]], false, ApiResponse::UNAUTHORIZED);
        }

        return redirect()->guest('/login');
    }
}
