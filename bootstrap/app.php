<?php


use App\Http\Middleware\IsActive;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsKyced;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'user.active' => IsActive::class,
            'user.is_kyced' => IsKyced::class,
            'user.admin' => IsAdmin::class,
            'guest' => RedirectIfAuthenticated::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            '/palmpay/webhook',
            '/monnify/webhook',
            '/update-bvn-enrollment-status',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // $exceptions->render(function (AuthenticationException $e, Request $request) {
        //     if ($request->wantsJson()) {
        //         return response()->json(['code' => '401', 'message' => 'Unauthenticated'], 401);
        //     }
        //     return redirect()->guest(route('auth.login'));
        // });

        $exceptions->render(function (RouteNotFoundException $e, Request $request) {

            if (str_contains($e->getMessage(), 'login')) {
                return redirect()->guest(route('auth.login'));
            }
        });
    })->create();
