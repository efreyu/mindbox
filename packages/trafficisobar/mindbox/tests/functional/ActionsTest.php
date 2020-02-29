<?php

namespace TrafficIsobar\Mindbox\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ActionsTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function user_can_send_task() {
        $configs = [
            'email' => config("mindbox.testUserLogin"),
            'password' => config("mindbox.testUserPassword"),
        ];

        if ($configs['email'] && $configs['password']) {
            // Авторизация
            $this->from('/auth')->post('/login', $configs);
            // Первый таск
            $taskOne = $this->get('/api/v1/action/task1');
            $taskOne->assertStatus(200);
            $taskOne->assertJson(['message' => 'Success']);
            // Третий таск
            $taskThree = $this->get('/api/v1/action/task3');
            $taskThree->assertStatus(200);
            $taskThree->assertJson(['message' => 'Success']);

        } else {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function user_can_not_send_task() {
        // Первый таск
        $taskOne = $this->get('/api/v1/action/task1');
        $taskOne->assertStatus(401);
        $taskOne->assertJson(['message' => 'Authorization required']);
        // Третий таск
        $taskThree = $this->get('/api/v1/action/task3');
        $taskThree->assertStatus(401);
        $taskThree->assertJson(['message' => 'Authorization required']);
    }
}
