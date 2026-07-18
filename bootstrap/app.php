<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {

            Route::middleware('web')
                ->namespace('App\Http\Controllers\Admin')
                ->group(base_path('routes/admin/web.php'));

            Route::middleware('web')
                ->namespace('App\Http\Controllers\Organization')
                ->group(base_path('routes/organization/web.php'));

            Route::middleware('web')
                ->namespace('App\Http\Controllers\Teacher')
                ->group(base_path('routes/teacher/web.php'));

            Route::middleware('web')
                ->namespace('App\Http\Controllers\Examiner')
                ->group(base_path('routes/examiner/web.php'));

            Route::prefix('api')
                ->name('api.')
                ->middleware(['api', 'localization'])
                ->namespace('App\Http\Controllers\Api')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'localization' => \App\Http\Middleware\Localization::class,
            'redirect_to_dashboard' => \App\Http\Middleware\RedirectToDashboard::class,
            'set_selected_organization' => \App\Http\Middleware\SetSelectedOrganization::class,
            'permission_with_team' => \App\Http\Middleware\CheckPermissionWithTeam::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (NotFoundHttpException $e, $request) {

            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->api(null, 1, 'endpoint does not exists', 404);
            }

            if (request()->ajax()) {

                return response()->json("not found", 404);

            }

            return response()->view('errors.404', [], 404);

        });

        $exceptions->render(function (AccessDeniedHttpException $e, $request) {

            if ($request->is('api/*')) {
                return response()->api(null, 1, 'this action is unauthorized', 403);
            }

        });

        $exceptions->render(function (ValidationException $e, $request) {

            if ($request->is('api/*')) {
                return response()->api(null, 1, $e->validator->errors()->first(), 422);
            }

        });

        $exceptions->render(function (AuthenticationException $e, $request) {

            if ($request->is('api/*')) {
                return response()->api(null, 1, 'this end point requires authentication', 401);
            }

        });

        $exceptions->render(function (ThrottleRequestsException $e, $request) {

            $retryAfter = $e->getHeaders()['Retry-After'] ?? 60;

            $minutes = ceil($retryAfter / 60);

            $seconds = $retryAfter;

            $message = trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => $minutes,
            ]);

            if ($request->wantsJson()) {

                return response()->api(null, 1, $message, 429);

            } else {

                throw ValidationException::withMessages([
                    'too_many_attempts' => $message,
                ]);

            }//end of else

        });

        $exceptions->render(function (HttpException $e, $request) {

            if ($e->getStatusCode() == 403 && $request->is('admin/*') && auth()->user()->hasRole('admin')) {

                if ($request->ajax()) {

                    return response()->json([
                        'view' => view('errors.500')->render(),
                        'modal_title' => __('site.access_forbidden')
                    ], 403);
                }

                return response()->view('admin.errors.403');

            } else if ($request->ajax() && config('app.env') == 'prod') {

                return response()->json([
                    'modal_view' => view('errors._500')->render(),
                    'modal_title' => __('site.error')
                ], 500);

            }

        });

        $exceptions->render(function (\NotificationChannels\FCM\Exception\HttpException $e, $request) {

            if ($request->ajax()) {

                return response()->json([
                    'modal_view' => view('errors._500')->render(),
                    'modal_title' => __('site.error')
                ], 500);
            }

            return response()->view('errors.500');

        });

        $exceptions->render(function (TokenMismatchException $e, $request) {

            return redirect()->route('login');

        });

    })->create();
