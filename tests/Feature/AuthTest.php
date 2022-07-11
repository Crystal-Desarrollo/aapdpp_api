<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    //TODO: test email address must be unique

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
        ];

        $response = $this->json("post", route("auth.register"), $userData);

        $userId = $response->json("user.id");

        $response->assertStatus(201);
        $response->assertJsonStructure(['name', 'email', 'avatar', 'role']);
        $response->assertJson(['name' => $userData['name'], 'email' => $userData['email']]);
        $this->assertDatabaseHas('users', ['name' => $userData['name'], 'email' => $userData['email']]);
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
        $this->app->get('auth')->forgetGuards(); //Make the test as guest

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
        $this->app->get('auth')->forgetGuards(); //Make the test as guest

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
        $response->assertSeeText("Usuario o contraseña incorrecto");
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
