<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/questions/all', [QuestionController::class, 'getAllQuestions']);
    Route::post('/questions/evaluate', [QuestionController::class, 'evaluateAnswers']);
});

Route::get('/top-users', [UserController::class, 'getTopUsers']);
