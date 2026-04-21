<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_is_public(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Entrar no painel');
    }

    public function test_login_authenticates_the_user(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('Secret123!'),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'Secret123!',
        ])->assertRedirect(route('users.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_protected_pages_redirect_guests_to_login(): void
    {
        $this->get('/users')
            ->assertRedirect(route('login'));
    }
}
