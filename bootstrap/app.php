<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->is('admin/login') && $request->isMethod('post')) {
                $wait = (int) ($e->getHeaders()['Retry-After'] ?? 0);
                $mins = $wait > 0 ? (int) ceil($wait / 60) : null;
                $msg = 'Too many login attempts. ' . ($mins
                    ? 'Please try again in about ' . $mins . ($mins === 1 ? ' minute.' : ' minutes.')
                    : 'Please wait a little and try again.');
                return redirect()->route('login')->withErrors(['email' => $msg])->withInput($request->only('email'));
            }
        });
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
