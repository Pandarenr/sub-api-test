<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\AuthController;

Route::post('/subscriptions/rubrics/{rubric_id}/{email}', [SubscriptionController::class, 'subscribe']);
Route::delete('/subscriptions/rubrics/{rubric_id}/{email}', [SubscriptionController::class, 'unsubscribe']);
Route::delete('/subscriptions/users/{email}', [SubscriptionController::class, 'unsubscribeUser']);
Route::middleware('auth:sanctum')->get('/subscriptions/rubrics/{rubric_id}', [SubscriptionController::class, 'getRubricSubscriptions']);
Route::middleware('auth:sanctum')->get('/subscriptions/users/{email}', [SubscriptionController::class, 'getUserSubscriptions']);
Route::post('/register-application', [AuthController::class, 'registerApp']);