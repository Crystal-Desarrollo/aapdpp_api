<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Chck the required fields on register
     *
     * @return void
     */
    public function test_register_required_fields()
    {

        //Check Name is required
        $response = $this->json("post", route("auth.register"), [
            'email' => "test@example.com",
            'password' => "abc123",
            'password_confirmation' => "abc123",
        ]);
        $response->assertStatus(422);
        $response->assertSeeText("The name field is required.");

        //Check email is required
        $response = $this->json("post", route("auth.register"), [
            'name' => "test",
            'password' => "abc123",
            'password_confirmation' => "abc123",
        ]);
        $response->assertStatus(422);
        $response->assertSeeText("The email field is required.");

        //Check password is required
        $response = $this->json("post", route("auth.register"), [
            'name' => "test",
            'email' => "test@example.com",
            'password_confirmation' => "abc123",
        ]);
        $response->assertStatus(422);
        $response->assertSeeText("The password field is required.");

        //Check password_confirmation is required
        $response = $this->json("post", route("auth.register"), [
            'name' => "test",
            'email' => "test@example.com",
            'password' => "abc123",
        ]);
        $response->assertStatus(422);
        $response->assertSeeText("The password confirmation does not match.");

        //Check password_confirmation must be same as password
        $response = $this->json("post", route("auth.register"), [
            'name' => "test",
            'email' => "test@example.com",
            'password' => "abc123",
            'password_confirmation' => "abc123456",
        ]);
        $response->assertStatus(422);
        $response->assertSeeText("The password confirmation does not match.");
    }

    /**
     * An user can be created
     *
     * @return void
     */
    public function test_user_can_be_created()
    {
        $userData = [
            'name' => "John Doe",
            'email' => "test@example.com",
            'password' => "abc123",
            'password_confirmation' => "abc123",
        ];

        $response = $this->json("post", route("auth.register"), $userData);

        $userId = $response->json("user.id");

        $response->assertStatus(201);
        $response->assertJsonStructure(['user' => ['name', 'email'], 'token']);
        $response->assertJson(['user' => ['name' => $userData['name'], 'email' => $userData['email']]]);
        $this->assertDatabaseHas('users', ['name' => $userData['name'], 'email' => $userData['email']]);
        $this->assertDatabaseHas('personal_access_tokens', ['tokenable_type' => User::class, 'tokenable_id' => $userId]);
    }

    /**
     * Chck the required fields on login
     *
     * @return void
     */
    public function test_login_required_fields()
    {
        //TODO: Test
    }

    /**
     * An user can login
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $password = '123abc';

        $userData = [
            'name' => "John Doe",
            'email' => "test@example.com",
            'password' => bcrypt($password)
        ];
        $users = User::factory()->count(1)->create($userData);
        $user = $users[0];

        $response = $this->json("post", route('auth.login'), [
            'email' => $userData['email'],
            'password' => $password
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['user' => ['name', 'email'], 'token']);
        $response->assertJson(['user' => ['name' => $user->name, 'email' => $user->email]]);
    }

    /**
     * Test invalid credentials
     *
     * @return void
     */
    public function test_invalid_credentials_are_rejected()
    {
        $password = '123abc';

        $userData = [
            'name' => "John Doe",
            'email' => "test@example.com",
            'password' => bcrypt($password)
        ];
        $users = User::factory()->count(1)->create($userData);
        $user = $users[0];

        // Check with wrong email
        $response = $this->json("post", route('auth.login'), [
            'email' => "another@email.example.com",
            'password' => $password
        ]);

        $response->assertUnauthorized();
        $response->assertSeeText("Invalid credentials");

        //Check with wrong password
        $response = $this->json("post", route('auth.login'), [
            'email' => $user->email,
            'password' => 'wrongpassword'
        ]);

        $response->assertUnauthorized();
        $response->assertSeeText("Invalid credentials");
    }

    /**
     * Chck the user can logout
     *
     * @return void
     */
    public function test_logout()
    {
        //TODO: Test
    }
}
