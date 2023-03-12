<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Response;

class AuthController extends Controller
{
    public function registerApp(Request $request)
    {
        // Проверяем существует ли приложение. Если нет создаём.
        $application = Application::firstOrCreate(['name' => $request->name]);
        // Создаём токен аутентификации для приложения. Возращаем токен.
        $token = $application->createToken($request->name);
        $response = ['token' => substr($token->plainTextToken, 2)];
        return Response::json($response, 200);
    }
}
