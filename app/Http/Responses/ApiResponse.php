<?php

namespace App\Http\Responses;

use App\Contracts\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiResponse
{

    /**
     * @param $data
     * @param string $message
     * @return JsonResponse
     */
    public static function created($data = null, $message = 'successfully created')
    {
        return response()->json([
            'status'    => true,
            'response'  => $data,
            'message'   => $message
        ], Response::HTTP_CREATED);
    }



    /**
     * @param null $data
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success(
        $data = null,
        int $status = 200,
        string $message = 'Sucess',
        $paginationData = null
        ): JsonResponse
    {
        $response = [
            'status' => true,
            'response' => $data,
            'message' => $message,
        ];

        if ($paginationData !== null) {
            $response['pagination'] = $paginationData;
        }

        return response()->json($response, $status);
    }

    /**
     * @param $data
     * @param string $message
     * @param int $status
     * @param bool $paramError
     * @return JsonResponse
     */
    public static function error(
        $data = null,
        $message = 'Error',
        $status = Response::HTTP_BAD_REQUEST,
        $paramError = false): JsonResponse
    {

        $status = $status > 0 ? $status : Response::HTTP_BAD_REQUEST;
        return response()->json([
            'status'        => false,
            'response'      => $data,
            'message'       => $message,
            'paramError'    => $paramError
        ], $status);
    }


}
