<?php

namespace TrafficIsobar\Mindbox\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TrafficIsobar\Mindbox\app\Http\Classes\Helper;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    public function a_user_can_not_login() {

        $wrongData = [
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password(12, 20),
        ];

        $this->post('/api/v1/user/auth', $wrongData)->assertStatus(401);

    }

    /** @test */
    public function a_user_can_login_via_api() {
        $configs = [
            'email' => config("mindbox.testUserLogin"),
            'password' => config("mindbox.testUserPassword"),
        ];

        if ($configs['email'] && $configs['password']) {
            $this->post('/api/v1/user/auth', $configs)->assertStatus(200);
        } else {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function a_user_send_wrong_data_via_api() {
        $wrongData = [
            'email' => $this->faker->word,
        ];

        $data = $this->post('/api/v1/user/auth', $wrongData);
        $data->assertStatus(401);
        $json = Helper::json_decode($data->getContent());
        $this->assertTrue(is_array($json));
        if ($json) {
            $this->assertTrue(array_key_exists('email', $json));
            $this->assertTrue(array_key_exists('password', $json));
        }
    }

    /** @test */
    public function call_auth_page() {
        $response = $this->call('GET', '/auth');
        $this->assertEquals(200, $response->status());
    }

    /** @test */
    public function a_user_can_see_index_page() {
        $response = $this->get('/');
        $response->assertSuccessful();
        $response->assertViewIs('mindbox::app');
    }

    /** @test */
    public function a_user_can_see_auth_page() {
        $response = $this->get('/auth');
        $response->assertSuccessful();
        $response->assertViewIs('mindbox::app');
    }

    /** @test */
    public function a_user_can_login_via_web() {
        $configs = [
            'email' => config("mindbox.testUserLogin"),
            'password' => config("mindbox.testUserPassword"),
        ];

        if ($configs['email'] && $configs['password']) {
            $response = $this->from('/login')->post('/login', $configs);
            $response->assertRedirect('/');
        } else {
            $this->assertTrue(true);
        }
    }

    #/** @test */
    public function a_user_send_wrong_data_via_web() {
        $wrongData = [
            'email' => $this->faker->word,
        ];

        $response = $this->from('/login')->post('/login', $wrongData);
        $response->dump();
        $response->assertStatus(401);
        $json = Helper::json_decode($response->getContent());
        $this->assertTrue(is_array($json));
        if ($json) {
            $this->assertTrue(array_key_exists('email', $json));
            $this->assertTrue(array_key_exists('password', $json));
        }
    }
}
