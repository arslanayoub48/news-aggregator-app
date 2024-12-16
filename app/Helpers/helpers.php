<?php


use Illuminate\Support\Facades\Log;

if (!function_exists('getLoggedInUser')) {
    function getLoggedInUser()
    {
        try {
            return auth()->user();
        } catch (\Exception $e) {
            Log::error('Exception occurred while fetching New York time.', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

}
if (!function_exists('getLoggedInUserId')) {
    function getLoggedInUserId()
    {
        try {
            return auth()->user()->id;
        } catch (\Exception $e) {
            Log::error('Exception occurred while fetching New York time.', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

}
