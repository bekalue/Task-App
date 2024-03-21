<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tag;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/api/tags');

        $response->assertStatus(200);
        $response->assertJson([$tag->toArray()]);
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/tags', ['name' => 'new tag']);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tags', ['name' => 'new tag', 'user_id' => $user->id]);
    }

    public function testShow()
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/api/tags/{$tag->id}");

        $response->assertStatus(200);
        $response->assertJson($tag->toArray());
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put("/api/tags/{$tag->id}", ['name' => 'updated tag']);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tags', ['name' => 'updated tag', 'user_id' => $user->id]);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/api/tags/{$tag->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
}

