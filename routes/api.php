<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SubscriptionController;

Route::post('/subscriptions/rubrics/{rubric_id}/{email}', [SubscriptionController::class, 'subscribe']);
Route::delete('/subscriptions/rubrics/{rubric_id}/{email}', [SubscriptionController::class, 'unsubscribe']);
Route::delete('/subscriptions/users/{email}', [SubscriptionController::class, 'unsubscribeUser']);
Route::get('/subscriptions/rubrics/{rubric_id}', [SubscriptionController::class, 'getRubricSubscriptions']);
Route::get('/subscriptions/users/{email}', [SubscriptionController::class, 'getUserSubscriptions']);