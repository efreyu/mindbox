<?php


namespace TrafficIsobar\Mindbox\app\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class ActionController
{
    /**
     * @param $operation string
     * @return \Illuminate\Http\JsonResponse
     */
    private function sendTestActionRequest($operation) {
        $result = [
            'code' => 401,
            'data' => ['message' => 'Authorization required'],
        ];
        if (Auth::guest()) {
            return response()->json($result['data'], $result['code']);
        }

        $userInfo['customer'] = [
            'ids' => [
                'mindboxId' => Auth::user()->getId()
            ]
        ];

        $response = \DirectCRM::sendRequest($operation, $userInfo);

        if ($response->isSuccess()) {
            $result['code'] = $response->getStatusCode();
            $result['data']['message'] = $response->getStatusMessage();
        }

        return response()->json($result['data'], $result['code']);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/action/task1",
     *     tags={"Активность 1"},
     *     summary="Запрос на активность 1",
     *     description="",
     *     operationId="taskOne",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(response=200, description="Пользователь авторизован, json {'message':''}"),
     *     @OA\Response(response=401, description="Логин или пароль не верный, json {'message':''}"),
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskOne(Request $request) {
        return $this->sendTestActionRequest('Jti.v3.TestAction1');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/action/task3",
     *     tags={"Активность 3"},
     *     summary="Запрос на активность 3",
     *     description="",
     *     operationId="taskThree",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(response=200, description="Пользователь авторизован, json {'message':''}"),
     *     @OA\Response(response=401, description="Логин или пароль не верный, json {'message':''}"),
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskThree(Request $request) {
        return $this->sendTestActionRequest('Jti.v3.TestAction3');
    }
}
