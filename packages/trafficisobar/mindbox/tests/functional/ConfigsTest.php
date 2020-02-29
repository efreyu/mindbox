<?php

namespace TrafficIsobar\Mindbox\Tests;

use Tests\TestCase;

class ConfigsTest extends TestCase
{
    /** @test */
    public function test_for_read_config() {
        $configs = [
            'timeout',
            'endpointId',
            'secretKey',
            'baseUrl',
        ];


        foreach ($configs as $config) {
            $this->assertTrue(null != config("mindbox.{$config}") && '' != config("mindbox.{$config}"));
        }
    }
}
