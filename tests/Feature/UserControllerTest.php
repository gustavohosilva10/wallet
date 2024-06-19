<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testRegisterUserSuccess()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'document' => '12345678901',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
            'success',
                'user' => [
                    'name',
                    'email',
                    'document',
                ]
            ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('wallets', ['user_id' => $response->json('user.id'), 'balance' => 0]);
    }

    public function testRegisterUserValidationFails()
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'invalid-email',
            'document' => '1234',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'name',
                'email',
                'document',
            ]);
    }
}
