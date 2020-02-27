<?php


namespace TrafficIsobar\Mindbox\app\Http\Classes;


class Helper
{
    /**
     * Возращает false, если не получилось декодировать json
     * @param $json
     * @param bool $returnError
     * @return bool|mixed
     */
    public static function json_decode($json)
    {
        try {
            return json_decode($json, true);
        } catch (\Exception $e) {
            return false;
        }
    }
}
