<?php

use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: '',
        then: function (): void {
            Route::middleware('api')
                ->prefix('api')
                ->name('api.')
                ->group(base_path('routes/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            $previous = $e->getPrevious();

            if (! $previous instanceof ModelNotFoundException) {
                return null;
            }

            if ($previous->getModel() !== Task::class) {
                return null;
            }

            if (! $request->routeIs('tasks.*', 'api.tasks.*')) {
                return null;
            }

            return response()->json([
                'message' => 'Task not found.',
            ], 404);
        });

        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            return $request->routeIs('tasks.*', 'api.tasks.*')
                || $request->is('tasks', 'tasks/*', 'api/tasks', 'api/tasks/*');
        });
    })->create();
