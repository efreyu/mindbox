<?php

namespace TrafficIsobar\Mindbox\app\Http\Controllers;


use Validator,
    Illuminate\Http\Request,
    Illuminate\Routing\Controller;


class AuthController
{
    /**
     * @link https://developers.mindbox.ru/docs/аутентификация-по-паролю
     *
     * @OA\Post(
     *     path="/api/1.0/user/auth",
     *     tags={"Авторизация"},
     *     summary="Авторизация по логину и паролю",
     *     description="",
     *     operationId="authUser",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={"email": "test.email@example.com","password": "123456"}
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Пользователь авторизован, json {'message':''}"),
     *     @OA\Response(response=400, description="Данные не валидны, json {'message':''}"),
     *     @OA\Response(response=401, description="Логин или пароль не верный, json {'message':''}"),
     *     @OA\Response(response=500, description="Ошибка интеграции c CRM, json {'message':''}"),
     *     @OA\Response(response=503, description="CRM не доступна, json {'message':''}"),
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $requestFields = $request->all();
        $validator = Validator::make(
            $requestFields,
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors()->toArray(), 400);
        }

        $userInfo['customer'] = [
            'email' => $requestFields['email'],
            'password' => $requestFields['password'],
        ];
        $result = \DirectCRM::sendRequest('Jti.v3.CustomerAuthentication', $userInfo);

        return response()->json($result['data'], $result['code']);
    }
}
