<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $exception) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
        });

        $exceptions->render(function (ModelNotFoundException $exception) {
            return response()->json([
                'message' => 'Resource not found',
            ], 404);
        });

        $exceptions->render(function (HttpException  $exception) {
            return response()->json([
                'message' => $exception->getMessage() ?: 'An error occurred',
            ], $exception->getStatusCode());
        });

        $exceptions->render(function (AuthenticationException $exception) {
            return response()->json([
                'message' => 'Authentication token is required or invalid.',
            ], 403);
        });

        $exceptions->render(function (\Throwable $exception) {
            Log::critical($exception->getMessage());
            return response()->json([
                'message' => 'Internal server error',
            ], 500);
        });
    })->create();
