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
     *                 @OA\Property(property="email", type="string", example="test.email@example.com"),
     *                 @OA\Property(property="password", type="string", example="123456"),
     *                 example={"email": "test.email@example.com","password": "123456"}
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Пользователь авторизован, json {'message':''}"),
     *     @OA\Response(response=401, description="Логин или пароль не верный, json {'message':''}"),
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userAuth(Request $request)
    {
        $result = [
            'code' => 401,
            'data' => ['message' => 'Логин или пароль указаны не верно'],
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
            $result['data']['message'] = 'Вы успешно авторизованы';
        }

        return response()->json($result['data'], $result['code']);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Inertia\Response
     */
    public function index()
    {
        if (Auth::guest()) {
            return Inertia::render('Auth/Index');
        } else {
            return redirect()->route('mindbox.home')->with('successMessage', 'Вы уже авторизованы!');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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
            return redirect()->route('mindbox.auth.index')->with('errors', $validator->errors()->toArray());
        }

        $credentials = [
            'username' => $requestFields['email'],
            'password' => $requestFields['password'],
        ];
        if (Auth::attempt($credentials)) {
            return redirect()->route('mindbox.home')->with('successMessage', 'Вы успешно авторизовались!');
        }

        return redirect()->route('mindbox.auth.index')->with('errors', 'Логин или пароль введены не верно!');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout() {
        Auth::logout();
        \Session::forget('user');

        return redirect()->route('mindbox.home');
    }
}
