<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function (Request $request) {
            return $request->is('api/*') ? '/api/login' : '/login';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ModelNotFoundException $e, $request){
            if ($request->is('api/*')){
                return response()->json([
                    'success' => false,
                    'message'=> 'Data not found',
                    'data' => null,
                ], 404);
            }
        });

    $exceptions->render(function(ValidationException $e, $request){
        if ($request->is('api/*')){
            return response()->json([
                'success'=> false,
                'message'=>'Validation failed',
                'errors'=> $e->errors(),
            ], 422);
        }
    });

    $exceptions->render(function (AuthenticationException $e, $request) {
        if ($request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'data' => null,
            ], 401);
        }
    });

    $exceptions->render(function(HttpExceptionInterface $e, $request){
        if ($request->is('api/*')){
            return response()->json([
                'success'=>false,
                'message'=> $e->getMessage() ? : 'HTTP Error',
                'data'=>null,
            ], $e->getStatusCode());
        }
    });

    $exceptions->render(function (\Throwable $e, $request) {
        if ($request->is('api/*')) {
            $message = config('app.debug') && $e->getMessage()
                ? $e->getMessage()
                : 'Internal Server Error';

            return response()->json([
                'success' => false,
                'message' => $message,
                'data' => null,
            ], 500);
        }
    });
    })->create();
