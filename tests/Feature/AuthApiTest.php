<?php

namespace Tests\Feature;

use App\Http\Responses\ApiResponse;
use Tests\BaseApiTest;

class AuthApiTest extends BaseApiTest
{
    private string $tableToken = 'oauth_access_tokens';

    public function test_login_success()
    {
        $data = [
            'email' => $this->user->email,
            'password' => '12345678'
        ];

        $response = $this->postJson(route('login'), $data)
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['user', 'access_token', 'expires_at']
            ]);

        $this->assertNotEmpty($response->json('data')['access_token']);
    }

    public function test_login_expectFailLogin()
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'testtest'
        ];

        $this->postJson(route('login'), $data)
            ->assertStatus(ApiResponse::BAD_REQUEST)
            ->assertJson([
                'error' => [
                    'message' => [
                        'Login Failed!'
                    ]
                ]
            ]);
    }

    public function test_login_expectValidationError()
    {
        $this->postJson(route('login'), [])
            ->assertStatus(ApiResponse::VALIDATION)
            ->assertJsonValidationErrors(['password', 'email']);
    }

    public function test_login_invalidEmail_expectValidationError()
    {
        $data = [
            'email' => 'sdfsdfsdfdf',
            'password' => '12345678'
        ];
        $this->postJson(route('login'), $data)
            ->assertStatus(ApiResponse::VALIDATION)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_logout_success()
    {
        $data = [
            'email' => $this->user->email,
            'password' => '12345678',
        ];

        // Login
        $response = $this->postJson(route('login'), $data)
            ->assertOk();

        $this->withHeaders(['Authorization' => 'Bearer ' . $response->json('data')['access_token']])
            ->getJson(route('logout'))
            ->assertOk();
        $this->assertDatabaseHas($this->tableToken, ['user_id' => $this->user->id, 'revoked' => 1]);
    }

    public function test_register_expectSuccess()
    {
        $response = $this->postJson(route('register', [
            'email' => 'test' . rand(1, 10) . '@test.com',
            'password' => 'Pass@123',
            'password_confirmation' => 'Pass@123',
            'name' => 'test',
        ]))->assertOk()
            ->assertJsonStructure([
                'data' => ['user', 'access_token', 'expires_at']
            ]);

        $this->assertNotEmpty($response->json('data')['access_token']);
    }

    public function test_register_expectValidationErrors()
    {
        $this->postJson(route('register', []))
            ->assertStatus(ApiResponse::VALIDATION)
            ->assertJsonValidationErrors(['password', 'email', 'name']);
    }

    public function test_register_alreadyExistsEmail_expectValidationErrors()
    {
        $this->postJson(route('register', [
            'email' => $this->user->email,
            'password' => 'Pass@123',
            'password_confirmation' => 'Pass@123',
            'name' => 'test',
        ]))
            ->assertStatus(ApiResponse::VALIDATION)
            ->assertJsonValidationErrors(['email']);
    }
}
