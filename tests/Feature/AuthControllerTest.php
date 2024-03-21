<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the register method of AuthController.
     *
     * @return void
     */
    public function testRegister()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/api/register', $userData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['user' => ['id', 'name', 'email', 'created_at', 'updated_at'], 'access_token']);

        $this->assertDatabaseHas('users', ['name' => $userData['name'], 'email' => $userData['email']]);
    }

    /**
     * Test the login method of AuthController.
     *
     * @return void
     */
    public function testLogin()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->post('/api/login', $loginData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['user' => ['id', 'name', 'email', 'created_at', 'updated_at'], 'access_token']);
    }

    /**
     * Test the login method of AuthController with invalid credentials.
     *
     * @return void
     */
    public function testLoginWithInvalidCredentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ];

        $response = $this->post('/api/login', $loginData);

        $response->assertStatus(401);
    }
}

