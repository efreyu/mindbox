<?php

namespace TrafficIsobar\Mindbox\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TrafficIsobar\Mindbox\app\Http\Classes\Helper;

class AccessTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    public function access_to_site() {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
