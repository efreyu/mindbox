<?php

namespace TrafficIsobar\Mindbox\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class AccessTest extends TestCase
{
    use WithFaker;


    /** @test */
    public function access_to_site() {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function access_to_auth_pahe() {
        $response = $this->get('/auth');

        $response->assertStatus(200);
    }
}
