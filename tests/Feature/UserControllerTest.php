<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index method of UserController.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/user');

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'name', 'email', 'created_at', 'updated_at']);
    }

    /**
     * Test the update method of UserController.
     *
     * @return void
     */
    public function testUpdate()
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
        ];

        $response = $this->actingAs($user)->put('/api/user', $updateData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'name', 'email', 'created_at', 'updated_at']);

        $this->assertDatabaseHas('users', ['name' => $updateData['name'], 'email' => $updateData['email']]);
    }

    /**
     * Test the destroy method of UserController.
     *
     * @return void
     */
    public function testDestroy()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete('/api/user');

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
