<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Validation\ValidationException;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            // 🔁 Gestion propre des erreurs de validation
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'errors' => $exception->errors()
                ], 422);
            }

            // 🔁 Gestion des erreurs HTTP comme 403, 404, etc.
            if ($exception instanceof HttpExceptionInterface) {
                return response()->json([
                    'error' => $exception->getMessage()
                ], $exception->getStatusCode());
            }

            // 🔁 Par défaut, toutes les autres erreurs
            return response()->json([
                'error' => $exception->getMessage()
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
