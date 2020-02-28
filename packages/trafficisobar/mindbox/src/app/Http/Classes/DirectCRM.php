<?php

namespace TrafficIsobar\Mindbox\app\Http\Classes;

use TrafficIsobar\Mindbox\app\Http\Classes\SuccessResponse;
use TrafficIsobar\Mindbox\app\Http\Classes\ErrorResponse;

class DirectCRM
{

    /**
     * Формирует массив с параметрами запроса
     *
     * @param array $arrParams
     * @param bool $includeMindboxDeviceUUID
     * @return array
     */
    public function buildQuery($arrParams = [], $includeMindboxDeviceUUID = true)
    {
        $params = [
            'endpointId' => config('mindbox.endpointId')
        ];
        if ($includeMindboxDeviceUUID && !isset($arrParams['deviceUUID'])) {
            $params['deviceUUID'] = \Cookie::get('mindboxDeviceUUID');
        } elseif (isset($arrParams['deviceUUID']) && empty($arrParams['deviceUUID'])) {
            unset($arrParams['deviceUUID']);
        }
        if (!empty($arrParams)) {
            $params = array_replace_recursive($params, $arrParams);
        }
        return $params;
    }


    /**
     * Формирует заголовок запроса
     *
     * @param string $type
     * @return array
     */
    public function buildHeaders($type = 'json')
    {
        $headers = [
            'Authorization' => 'Mindbox secretKey="' . config('mindbox.secretKey') . '"',
            'User-Agent' => \Request::server('HTTP_USER_AGENT'),
            'X-Customer-IP' => \Request::server('REMOTE_ADDR')
        ];
        switch ($type) {
            case 'xml':
                break;
            default:
                {
                    $headers['Accept'] = 'application/json';
                    $headers['Content-Type'] = 'application/json';
                }
                break;
        }
        return $headers;
    }

    public function requestToCRM($method = 'GET', $request = [], $async = false, $url = false)
    {
        try {
            $guzzle = new \GuzzleHttp\Client();
            if (empty($url)) {
                $url = config('mindbox.baseUrl') . ($async ? 'async' : 'sync');
            }
            $start = explode(' ', microtime());
            $response = $guzzle->request($method, $url, $request);
            $result = $response->getBody()->getContents();
            $finish = explode(' ', microtime());
            $info = [
                'time' => \Jenssegers\Date\Date::now(),
                'url' => $method . ' ' . $url
            ];
            $mindboxId = (!empty($request['json']['customer']['ids']['mindboxId']) ? ' [MindboxId: ' . $request['json']['customer']['ids']['mindboxId'] . ']' : '');
            $operation = (!empty($request['query']['operation']) ? ' ' . $request['query']['operation'] : '');

            $res = Helper::json_decode($result);
            if ($res !== false && is_array($res)) {
                $info['response'] = $res;
            } else {
                try {
                    if ($resultXml = new \SimpleXMLElement($result)) {
                        $info['response'] = Helper::xml2Array($resultXml);
                    }
                } catch (\Exception $e) {
                }
                if (empty($info['response'])) {
                    $info['response'] = $result;
                }
            }
            $info['request'] = $request;
            \Log::debug($mindboxId . ' ' . $operation . ' - ' . (($finish[1] - $start[1]) + ($finish[0] - $start[0])) . ' sec
', $info);
            return $result;
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $info = [
                'time' => \Jenssegers\Date\Date::now(),
                'url' => $method . ' ' . $url,
                'message' => $e->getMessage(),
                'request' => $request
            ];
            \Log::critical('Ошибка CURL:', $info);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $info = [
                'time' => \Jenssegers\Date\Date::now(),
                'url' => $method . ' ' . $url,
                'message' => $e->getMessage(),
                'response' => $response->getBody()->getContents(),
                'request' => $request
            ];
            \Log::critical('Ошибка CURL:', $info);
            return false;
        }
    }

    /**
     * Базовый метод с использованием обёртки
     * @link https://developers.mindbox.ru/docs/json#section-описание-метода
     *
     * @param $operation
     * @param bool $userInfo
     * @param bool $includeMindboxDeviceUUID
     * @param bool $async
     * @return ErrorResponse|SuccessResponse
     */
    public function sendRequest($operation, $userInfo = false, $includeMindboxDeviceUUID = false, $async = false)
    {
        $request = [
            'query' => $this->buildQuery(['operation' => $operation], $includeMindboxDeviceUUID),
            'headers' => $this->buildHeaders(),
            'http_errors' => false
        ];
        if ($userInfo !== false) {
            $request['json'] = $userInfo;
        }
        if ($res = $this->requestToCRM('POST', $request, $async)) {
            if ($res = Helper::json_decode($res)) {
                if (isset($res['status']) && $res['status'] == 'Success') {
                    return new SuccessResponse($res);
                }
            }
        }
        return new ErrorResponse($res);
    }

}
