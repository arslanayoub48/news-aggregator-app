<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait ApiResponse
{
    public function UserSuccess($message, $data, $token, $status = 200)
    {
        Log::info($message);
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'token' => $token,
        ], $status);
    }

    public function fetchSuccess($data = [], $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $status);
    }
    public function success($message, $data = [], $status = 200)
    {
        Log::info($message);
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public function singleSuccessResponse($message, $status = 200)
    {
        Log::info($message);
        return response()->json([
            'success' => true,
            'message' => $message,
        ], $status);
    }

    public function failure($message, $exception = null, $status = 422)
    {
        if ($exception != null) {
            Log::error("Exception in" .  $exception->getMessage() . " (Line: " . $exception->getLine() . ")");
            Log::error("File : " . $exception->getFile());
        }
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
