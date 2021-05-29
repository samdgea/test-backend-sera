<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
//        if ($request->wantsJson()) {
            if ($exception instanceof AccessDeniedHttpException) {
                return response()->json([
                    'success' => false,
                    'errorMessage' => 'This action was not authorized',
                ], 403);
            } elseif ($exception instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'errorMessage' => 'The request did not pass the requirement',
                    'errorDetail' => $exception->errors()
                ], 422);
            } elseif ($exception instanceof AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'errorMessage' => 'Authorization is required to process this request'
                ], 403);
            } elseif ($exception instanceof AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'errorMessage' => 'Unauthenticated access'
                ], 401);
            } elseif($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'success' => false,
                    'errorMessage' => 'Unknown API call'
                ], 404);
            } else {
                return response()->json([
                    'success' => false,
                    'errorMessage' => 'An ' . get_class($exception). ' exception has occured',
                    'errorDetail' => $exception->getTrace()
                ], 500);
            }
//        } else {
//            Log::error("An " . get_class($exception) . " exception has occurred", $exception->getTrace());
//            return redirect()->to('https://abdilah.dev');
//        }
//        return parent::render($request, $exception);
    }
}
