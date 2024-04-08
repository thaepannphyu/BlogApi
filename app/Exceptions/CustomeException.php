<?php

namespace App\Exceptions;

use Exception;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomeException extends Exception
{



    public static function customerizeException(Exception $e, Request $request)
    {

        if ($request->expectsJson()) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    "message" =>  $e->getModel() . " Not Found",
                    "exception" =>  $e->getCode()
                ], 404);
            }
            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    "message" => "Not Found ",
                    "status" => $e->getStatusCode()
                ], 404);
            }
            if ($e instanceof ServerException) {
                return response()->json([
                    "message" => "Server error",
                    "status" => $e->getResponse()
                ], 500);
            }

            if ($e instanceof Exception) {
                return response()->json([
                    "message" => "Something went wrong "
                ], 500);
            }
        }
    }
}
