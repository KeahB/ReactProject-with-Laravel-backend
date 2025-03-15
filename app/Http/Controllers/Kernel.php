<?php
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class Kernel extends \Illuminate\Foundation\Http\Kernel {
    protected $middlewareGroups = [
        'api' => [
            EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];
    protected $routeMiddleware = [
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ];
    
}
