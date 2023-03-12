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
        // Проверяем существует ли рубрика
        $rubric = Rubric::where('id', $request->rubric_id)->firstOrfail();
        // Проверяем существует ли подписка. Добавляем если нет
        $subscription = Subscription::firstOrCreate(['rubric_id' => $rubric->id, 'user_id' => $subscriber->id]);
        return Response::json('You have been subscribed', 200);
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
        $subscriber = User::where('email', $request->email)->firstOrFail();
        // Проверяем существует ли рубрика
        $rubric = Rubric::where('id', $request->rubric_id)->firstOrfail();
        // Проверяем существует ли подписка.
        $subscription = Subscription::where(['rubric_id' => $rubric->id, 'user_id' => $subscriber->id])->firstOrFail();
        $subscriber->rubrics()->detach($request->rubric_id);
        return Response::json('You have been unsubscribed', 200);
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
        $subscriber = User::where('email', $request->email)->firstOrFail();
        $subscriber->rubrics()->detach();
        return Response::json('Your have been unsubscribe from all rubrics', 200);
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
        $rubric = Rubric::where('id', $request->rubric_id)->firstOrFail();
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
        $user = User::where('email', $request->email)->firstOrFail();
        // Формируем выдачу
        $subscriptions = Subscription::where('user_id', $user->id)->offset($offset)->limit($limit)->get();
        return Response::json($subscriptions, 200);
    }
}