<?php

namespace TrafficIsobar\Mindbox\app\Http\Controllers;


use Validator,
    Illuminate\Http\Request,
    Illuminate\Routing\Controller;


class AuthController extends Controller
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

        $result = [
            'code' => 401,
            'message' => '',
        ];
        $validator = Validator::make(
            $requestFields,
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors()->toArray(), 401);
        }

        $userInfo['customer'] = [
            'email' => $requestFields['email'],
            'password' => $requestFields['password'],
        ];
        $response = \DirectCRM::sendRequest('Jti.v3.CustomerAuthentication', $userInfo);

        if ($response->isAuthenticated()) {
            $result['code'] = 200;
            $result['message'] = 'Вы успешно авторизованы';
        } elseif ($response->isNotAuthenticated() || $response->isNotFound()) {
            $result['code'] = 401;
            $result['message'] = 'Логин или пароль указаны не верно';
        }
        $response = \DirectCRM::sendRequest('Jti.v3.CustomerAuthentication', $userInfo);

        return response()->json($result['message'], $result['code']);
    }
}
