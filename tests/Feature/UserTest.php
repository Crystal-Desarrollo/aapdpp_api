<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_user()
    {
        $this->withExceptionHandling();
        $user = User::factory()->create();
        $data = [
            'name' => 'test name',
            'email' => 'test@test.com',
            'phone' => "1234567890",
            'address' => 'Siempreviva 123',
            'additional_info' => Str::random(100)
        ];

        Sanctum::actingAs($user);
        $response = $this->json('put', route('users.update'), $data);

        $response->assertOk();
        $response->assertJson($data);

        array_push($data, [['id' => $user->id]]);
        $this->assertDatabaseHas('users', $data);
    }
}
