<?php

namespace App\Exceptions;

use App\Http\Responses\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->renderable(function (NotFoundHttpException $e) {
            return ApiResponse::error(
                [
                    'exception' => $e->getMessage()
                ],
                'Rota não encontrada', Response::HTTP_NOT_FOUND);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e) {
            return ApiResponse::error(
                $e->getMessage(),
                'Método HTTP não permitido',
                Response::HTTP_METHOD_NOT_ALLOWED
            );
        });

        $this->renderable(function (AuthenticationException $e) {
            return ApiResponse::error(
                $e->getMessage(),
                'Usuário não autenticado',
                Response::HTTP_UNAUTHORIZED
            );
        });

        $this->renderable(function (AccessDeniedHttpException $e) {
            return ApiResponse::error(
                $e->getMessage(),
                'Acesso negado',
                Response::HTTP_FORBIDDEN
            );
        });

        $this->renderable(function (ValidationException $e) {
            $errors = $e->errors();
            $firstError = $errors[array_key_first($errors)][0];
            return ApiResponse::error(
                $e->errors(),
                $firstError,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                true
            );
        });

        $this->renderable(function (Throwable $exception, Request $request) {
            $debug = env('APP_DEBUG')
                ? [
                    'message' => $exception->getMessage(),
                    'line'    => $exception->getLine(),
                    'code'    => $exception->getCode(),
                    'trace'   => $exception->getTrace(),
                ]
                : [];

            $data = [
                'exception' => $exception->getMessage(),
                'request'   => $request->fullUrl(),
                'debug'     => $debug,
            ];

            return ApiResponse::error(
                $data,
                'Erro interno no servidor',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        });
    }
}
