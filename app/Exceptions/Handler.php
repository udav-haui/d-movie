<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response|void
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NoChangedException) {
            return $exception->render($request);
        }
        if ($exception instanceof AuthorizationException) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 403,
                    'message' => __('This action is unauthorized.')
                ], 403);
            }
        }
        return parent::render($request, $exception);
    }

    protected function registerErrorViewPaths()
    {
        $paths = collect(config('view.paths'));
        if (\Request::segment(1) === config()->get('app.admin_path')) {
            foreach ($paths as $path) {
                $paths = $paths->diff($path);
                $paths = $paths->add($path . '/admin');
            }

        } else {
            foreach ($paths as $path) {
                $paths = $paths->diff($path);
                $paths = $paths->add($path . '/frontend');
            }
        }

        \View::replaceNamespace('errors', $paths->map(function ($path) {
            return "{$path}/errors";
        })->push(__DIR__.'/views')->all());
    }
}
