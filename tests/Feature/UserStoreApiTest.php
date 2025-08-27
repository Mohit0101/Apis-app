<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserStoreApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creates_a_user_successfully()
    {
        $response = $this->postJson('/api/create-user', [
            'name'     => 'John Doe',
            'email'    => 'john@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'sucess' => true,
                     'message' => 'user created successfully',
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function fails_when_name_is_missing()
    {
        $response = $this->postJson('/api/create-user', [
            'email'    => 'jane@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function fails_when_email_is_invalid()
    {
        $response = $this->postJson('/api/create-user', [
            'name'     => 'Jane Doe',
            'email'    => 'not-an-email',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function fails_when_email_is_not_unique()
    {
        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);

        $response = $this->postJson('/api/create-user', [
            'name'     => 'Another User',
            'email'    => 'duplicate@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function fails_when_password_is_too_short()
    {
        $response = $this->postJson('/api/create-user', [
            'name'     => 'Short Pass',
            'email'    => 'short@example.com',
            'password' => '123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }
}