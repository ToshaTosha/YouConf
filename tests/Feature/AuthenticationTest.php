<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'participant']);
    }

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');
        $response->assertInertia(fn($page) => $page->component('Auth/Register'));
    }

    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('user.show', User::first()->id));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertTrue(User::first()->hasRole('participant'));
    }

    public function test_registration_validation_errors()
    {
        $response = $this->post('/register', [
            'first_name' => '123Invalid',
            'last_name' => 'User',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertInertia(fn($page) => $page
            ->has('errors')
            ->where('errors.first_name.0', __('validation.alpha', ['attribute' => 'first name']))
            ->where('errors.email.0', __('validation.email', ['attribute' => 'email']))
            ->where('errors.password.0', __('validation.min.string', [
                'attribute' => 'password',
                'min' => 8
            ])));
    }

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');
        $response->assertInertia(fn($page) => $page->component('Auth/Login'));
    }

    public function test_users_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('Password123!')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('user.show', $user->id));
    }

    public function test_users_cannot_login_with_invalid_password()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertInertia(fn($page) => $page
            ->has('errors')
            ->where('errors.email.0', __('auth.failed')));
    }

    public function test_users_can_logout()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
