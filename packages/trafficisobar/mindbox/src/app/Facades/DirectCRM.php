<?php

namespace TrafficIsobar\Mindbox\app\Facades;

use \Illuminate\Support\Facades\Facade;

class DirectCRM extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'DirectCRM';
    }
}
