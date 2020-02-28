<?php

namespace TrafficIsobar\Mindbox\app\Http\Controllers;


use Auth;
use Inertia\Inertia;
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
     *     operationId="userAuth",
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
    public function userAuth(Request $request)
    {
        $result = [
            'code' => 401,
            'message' => 'Логин или пароль указаны не верно',
        ];

        $requestFields = $request->all();

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

        $credentials = [
            'username' => $requestFields['email'],
            'password' => $requestFields['password'],
        ];

        if (Auth::attempt($credentials)) {
            $result['code'] = 200;
            $result['message'] = 'Вы успешно авторизованы';
        }

        return response()->json($result['message'], $result['code']);
    }

    public function index()
    {
        return Inertia::render('Auth/Index');
    }

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
            return redirect()->route('mindbox.auth.index')->with('errors', $validator->errors()->toArray());
        }

        $credentials = [
            'username' => $requestFields['email'],
            'password' => $requestFields['password'],
        ];
        if (Auth::attempt($credentials)) {
            return redirect()->route('mindbox.home')->with('successMessage', 'yep!');
        }

        return redirect()->route('mindbox.auth.index')->with('errors', 'fail');
    }

    public function logout() {
        Auth::logout();
        \Session::forget('user');

        return redirect()->route('mindbox.home');
    }
}
