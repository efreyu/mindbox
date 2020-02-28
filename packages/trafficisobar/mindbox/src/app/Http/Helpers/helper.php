<?php

if (!function_exists('mindbox_asset')) {
    function mindbox_asset($path, $secure = null)
    {
        return route('mindbox.assets').'?path='.urlencode($path);
    }
}

