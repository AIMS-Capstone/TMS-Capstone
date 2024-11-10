<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        // Add global middleware here
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // Add middleware for web routes
        ],

        'api' => [
            // Add middleware for API routes
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'client.auth' => \App\Http\Middleware\ClientAuth::class,
        'client.organization' => \App\Http\Middleware\SetOrganization::class, // Add custom middleware here
    ];
}
