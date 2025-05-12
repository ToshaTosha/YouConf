<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Thesis;
use Spatie\Permission\Models\Role;
use Database\Seeders\StatusesTableSeeder;
use Database\Seeders\SectionSeeder;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(StatusesTableSeeder::class);
        $this->seed(SectionSeeder::class);

        // Создаем роли
        Role::create(['name' => 'participant']);
        Role::create(['name' => 'expert']);
    }
    public function test_show_user_profile()
    {
        $user = User::factory()->create()->assignRole('participant');
        Thesis::factory(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get(route('user.show', $user->id));

        $response->assertOk()
            ->assertInertia(
                fn($page) => $page
                    ->component('UserProfile')
                    ->has('theses', 3)
            );
    }

    public function test_edit_own_profile()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('user.edit', $user->id));

        $response->assertOk();
    }

    public function test_edit_other_profile_denied()
    {
        /** @var \App\Models\User $owner */
        $owner = User::factory()->create();
        /** @var \App\Models\User $intruder */
        $intruder = User::factory()->create();

        $response = $this->actingAs($intruder)
            ->get(route('user.edit', $owner->id));

        $response->assertForbidden();
    }

    public function test_update_profile_success()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['first_name' => 'OldName']);

        $response = $this->actingAs($user)
            ->put(route('user.update', $user->id), [
                'first_name' => 'NewName',
                'last_name' => 'NewLastName'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'NewName'
        ]);
        $response->assertRedirect(route('user.show', $user->id));
    }

    // Тест валидации обновления
    public function test_update_profile_validation()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->put(route('user.update', $user->id), [
                'first_name' => '123Invalid',
                'last_name' => 'Doe'
            ]);

        $response->assertInertia(fn($page) => $page->has('errors'));
    }
}
