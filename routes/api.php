<?php

use App\Http\Controllers\Api\v1\ArticleController;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Auth\PasswordResetController;
use App\Http\Controllers\Api\v1\PreferenceController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('password-reset', [PasswordResetController::class, 'requestPasswordReset'])->name('password.reset');
Route::post('password-reset/confirm', [PasswordResetController::class, 'resetPassword']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/search', [ArticleController::class, 'search']);
Route::get('/article/{uuid}', [ArticleController::class, 'show']);


Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::post('/preferences', [PreferenceController::class, 'setPreferences']);
    Route::get('/preferences', [PreferenceController::class, 'getPreferences']);
    Route::get('/personalized-feed', [PreferenceController::class, 'getPersonalizedFeed']);
});
