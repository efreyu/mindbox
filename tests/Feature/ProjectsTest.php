<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    public function a_user_can_not_login() {

        $wrongData = [
            'username' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password(12, 20),
        ];

        $data = $this->post('/api/v1/user/auth', $wrongData);
        $data->assertStatus(400);

    }

    /** @test */
    public function a_user_can_login() {
        $configs = [
            'email' => config("mindbox.testUserLogin"),
            'password' => config("mindbox.testUserPassword"),
        ];

        if ($configs['email'] && $configs['password']) {
            $data = $this->post('/api/v1/user/auth', $configs);
            dd($data->status());
        } else {
            $this->assertTrue(true);
        }
    }
}
