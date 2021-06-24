<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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

        $this->renderable(function (ValidationException $exception) {
            return response()->json([
                'message' => $exception->validator->errors()->first() ?: 'Данные неверны',
                'errors' => $exception->validator->errors()
            ], 422);
        });

        $this->renderable(function (AccessDeniedHttpException $exception) {
            if ($exception->getPrevious() and $exception->getPrevious()->getCode() == 422) {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], $exception->getPrevious()->getCode());
            }
        });
    }
}
