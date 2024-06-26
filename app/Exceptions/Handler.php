<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->renderable(function (Exception $e, Request $request) {
            return CustomeException::customerizeException($e, $request);
        });
    }

  
    public function render($request, Throwable $exception)
    {
        
        if ($request->expectsJson()) {
            if ($exception instanceof ValidationException) {
                return $this->invalidJson($request, $exception);
            }
            return $this->prepareJsonResponse($request, $exception);
        }

        return parent::render($request, $exception);
    }

}
