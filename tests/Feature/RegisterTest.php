<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    public function testsRegistersSuccessfully()
    {
        $payload = [
            'name' => 'Ali',
            'email' => 'ali@test.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->json('POST', 'api/register', $payload)
                ->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'user' => [
                            'name',
                            'email',
                            'updated_at',
                            'created_at',
                            'id'
                        ],
                        'api_token' 
                    ]
                ]);
    }

    public function testsRequiresPasswordEmailAndName()
    {
        $this->json('POST', 'api/register')
                ->assertStatus(422)
                ->assertJson([
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'name' => ['The name field is required.'],
                        'email' => ['The email field is required.'],
                        'password' => ['The password field is required.']
                    ]
                    
                ]);
    }

    public function testsRequirePasswordConfirmation()
    {
        $payload = [
            'name' => 'Ali',
            'email' => 'ali@test.com',
            'password' => 'password'
        ];

        $this->json('POST', 'api/register', $payload)
                ->assertStatus(422)
                ->assertJson([
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'password' => ['The password confirmation does not match.']
                    ]
                ]);
    }
}
