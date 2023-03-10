<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription;
use App\Models\Rubric;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    /**
     * Создание подписки пользователя на рубрику
     *
     * @param \Illuminate\Http\Request
     *
     * @return Response::json
     */
    public function subscribe(Request $request)
    {
        // Добавляем параметры из пути в запрос.
        $request->merge([
            'rubric_id' => $request->route('rubric_id'),
            'email' => $request->route('email')
        ]);
        // Если пользователя нет в базе, добавляем его
        $subscriber = User::firstOrCreate(['email' => $request->email]);
        // Проверяем существует ли подписка. Добавляем если нет
        $subscription = Subscription::where('rubric_id', $request->rubric_id)->where('user_id', $subscriber->id)->first();
        if (is_null($subscription)) {
            $subscriber->rubrics()->attach($request->rubric_id);
        }
        return Response::json('', 200);
    }

    /**
     * Удаление подписки пользователя на рубрику
     *
     * @param \Illuminate\Http\Request
     *
     * @return Response::json
     */
    public function unsubscribe(Request $request)
    {
        // Добавляем параметры из пути в запрос.
        $request->merge([
            'rubric_id' => $request->route('rubric_id'),
            'email' => $request->route('email')
        ]);
        // Проверяем существует ли пользователь
        $subscriber = User::where('email', $request->email)->first();
        // Проверяем существует ли подписка. Если да, удаляем её.
        $subscription = Subscription::where('rubric_id', $request->rubric_id)->where('user_id', $subscriber->id)->first();
        if (isset($subscription)) {
            $subscriber->rubrics()->detach($request->rubric_id);
        }
        return Response::json('', 200);
    }

    /**
     * Удаление всех подписок пользователя
     *
     * @param \Illuminate\Http\Request
     *
     * @return Response::json
     */
    public function unsubscribeUser(Request $request)
    {
        // Добавляем параметры из пути в запрос.
        $request->merge([
            'email' => $request->route('email')
        ]);
        // Проверяем существует ли пользователь
        $subscriber = User::where('email', $request->email)->first();
        if (isset($subscriber)) {
            $subscriber->rubrics()->detach();
        }
        return Response::json('', 200);
    }

    /**
     * Получить список подписок по рубрике.
     *
     * @param \Illuminate\Http\Request
     *
     * @return Response::json
     */
    public function getRubricSubscriptions(Request $request)
    {
        // Добавляем параметры из пути в запрос
        $request->merge([
            'rubric_id' => $request->route('rubric_id')
        ]);
        // Задаём параметры выдачи
        $limit = ((int)$request->limit)? : 5;
        $offset = ((int)$request->offset)? : null;
        $xml = ($request->xml)? : false;
        // Находим рубрику
        $rubric = Rubric::where('id', $request->rubric_id)->first();
        // Формируем выдачу
        $subscriptions = Subscription::where('rubric_id', $rubric->id)->offset($offset)->limit($limit)->get();
        return Response::json($subscriptions, 200);
    }

    /**
     * Получить список подписок по почте пользователя.
     *
     * @param \Illuminate\Http\Request
     *
     * @return Response::json
     */
    public function getUserSubscriptions(Request $request)
    {
        // Добавляем параметры из пути в запрос
        $request->merge([
            'email' => $request->route('email')
        ]);
        // Задаём параметры выдачи
        $limit = ((int)$request->limit)? : 5;
        $offset = ((int)$request->offset)? : null;
        $xml = ($request->xml)? : false;
        // Находим пользователя
        $user = User::where('email', $request->email)->first();
        // Формируем выдачу
        $subscriptions = Subscription::where('user_id', $user->id)->offset($offset)->limit($limit)->get();
        return Response::json($subscriptions, 200);
    }
}