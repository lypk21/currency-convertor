<?php


namespace App\Traits;



use App\Utils\Constants;

trait ApiResponser {


    protected function successResponse($data, $message = null, $code = Constants::HTTP_CODE_OK)
    {
        return response()->json([
            'status'=> 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message = null, $code = Constants::HTTP_CODE_ERROR)
    {
        return response()->json([
            'status'=>'Error',
            'message' => $message,
            'data' => null
        ], $code);
    }

}
