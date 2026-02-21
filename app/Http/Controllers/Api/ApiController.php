<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    protected function success($data, string $message='success', int $code=200, $meta=null):JsonResponse
    {
        $response = [
            'succes'=> true,
            'message'=> $message,
            'data'=>$data,

        ];

        if ($meta){

            $response['meta']=$meta;
        }

        return response()->json($response, $code);

    }

    protected function error(string $message='Something went wrong', int $code=400):JsonResponse
    {
        $response = [
            'succes' => true,
            'message'=>$message,
            'data'=>null,
        ];

        return response()->json($response,$code);
    }
}
