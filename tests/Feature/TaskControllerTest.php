<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Tag;


class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = User::factory()->create();
        $tasks = Task::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/api/tasks');

        $response->assertStatus(200);
        $response->assertJson($tasks->toArray());
    }

    public function testStore()
    {
        $user = User::factory()->create();
        $tags = Tag::factory()->count(3)->create(); // Create 3 tags

        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'tag_ids' => $tags->pluck('id')->toArray() // Use the IDs of the created tags
        ];

        $response = $this->actingAs($user)->post('/api/tasks', $taskData);

        $response->assertStatus(201);
        $response->assertJsonPath('title', $taskData['title']);
    }

    public function testShow()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/api/tasks/' . $task->id);

        $response->assertStatus(200);
        $response->assertJson($task->toArray());
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $tags = Tag::factory()->count(3)->create(); // Create 3 tags

        $updatedTaskData = [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'tag_ids' => $tags->pluck('id')->toArray() // Use the IDs of the created tags
        ];

        $response = $this->actingAs($user)->put('/api/tasks/' . $task->id, $updatedTaskData);

        $response->assertStatus(200);
        $response->assertJsonPath('title', $updatedTaskData['title']);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete('/api/tasks/' . $task->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

}
