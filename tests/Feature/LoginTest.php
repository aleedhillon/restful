<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{

    public function testRequiresEmailAndLogin()
    {
        $this->json('POST', 'api/login')
                ->assertStatus(422)
                ->assertJson([
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required']
                ]);
    }

    public function testUserLoginsSuccessfully()
    {
        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => bcrypt('password')
        ]);

        $payload = ['email' => 'testlogin@user.com', 'password' => 'password'];

        $this->json('POST', 'api/login', $payload)
                ->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'user' => [
                            'id',
                            'name',
                            'email',
                            'email_verified_at',
                            'created_at',
                            'updated_at'
                        ],
                        'api_token'
                    ]
                ]);
    }
}
