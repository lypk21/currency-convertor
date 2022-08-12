<?php

namespace App\Exceptions;

use App\Utils\Constants;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {

    }

    public function render($request, Throwable $e)
    {
        if(Str::contains($request->url(), "/api/")) {
            if($e instanceof NotFoundHttpException) {
                return response()->json([
                    'status'=>'Error',
                    'message' => $e->getMessage(),
                    'data' => null
                ],Constants::HTTP_CODE_NOT_FOUND);
            }
            if ($e instanceof ValidationException) {
                return response()->json([
                    'status'=>'Error',
                    'message' => $e->validator->getMessageBag()->first(),
                    'data' => null
                ], Constants::HTTP_CODE_ERROR);
            }
            if ($e instanceof AuthenticationException || $e instanceof RouteNotFoundException) {
                return response()->json([
                    'status'=>'Error',
                    'message' => 'No auth',
                    'data' => null
                ], Constants::HTTP_CODE_UNAUTHORIZED);
            }

            return response()->json([
                'status'=>'Error',
                'message' => $e->getMessage(),
                'data' => null
            ],Constants::HTTP_CODE_ERROR);
        }
        return parent::render($request, $e); // TODO: Change the autogenerated stub
    }

}