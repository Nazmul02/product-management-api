<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_registration()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_user_login()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function test_password_reset()
    {
        $user = User::factory()->create(['email' => 'john@example.com']);

        $this->postJson('/api/forgot-password', ['email' => 'john@example.com'])
            ->assertStatus(200);

        $token = Password::createToken($user);

        $this->postJson('/api/reset-password', [
            'email' => 'john@example.com',
            'token' => $token,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ])->assertStatus(200);
    }

    public function test_user_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/logout');
        $response->assertStatus(200);
    }
}
