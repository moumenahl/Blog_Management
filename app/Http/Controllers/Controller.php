<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function successResponse($data, string $message = 'تمت العملية بنجاح', int $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }
    protected function errorResponse(string $message = 'حدث خطأ ما', int $status = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}
