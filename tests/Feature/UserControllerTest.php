<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

   /** @test */
    public function it_can_list_users()
    {
        User::factory()->count(3)->create();
        $response = $this->get('/api/v1/users');
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

  
    /** @test */
    public function it_can_show_a_user()
    {
        $user = User::factory()->create();
        $response = $this->get("/api/v1/users/{$user->id}");
        $response->assertStatus(200);
        $response->assertJson(['data' => $user->toArray()]);
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $user = User::factory()->create();
        $updatedData = ['name' => 'jooscode'];

        $response = $this->put("/api/v1/users/{$user->id}", $updatedData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['name' => 'jooscode']);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create();
        $response = $this->delete("/api/v1/users/{$user->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}