<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    function a_user_can_login() {

        $wrongData = [
            'username' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password(12, 20),
        ];

        $data = $this->post('/api/v1/user/auth', $wrongData);
        $data->assertStatus(400);

    }
}
