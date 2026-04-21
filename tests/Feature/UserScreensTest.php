<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserScreensTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_shows_search_and_filters_results(): void
    {
        User::factory()->create([
            'name' => 'Alice Example',
            'email' => 'alice@example.com',
            'summary' => 'Archive keeper',
            'authority_level' => 'civic',
        ]);

        User::factory()->create([
            'name' => 'Bob Example',
            'email' => 'bob@example.com',
            'summary' => 'Field operator',
            'authority_level' => 'warrior',
        ]);

        $this->get('/users?search=Archive')
            ->assertOk()
            ->assertSee('Usuários Ativos')
            ->assertSee('name="search"', false)
            ->assertSee('Alice Example')
            ->assertDontSee('Bob Example');
    }

    public function test_create_page_renders_expected_form_fields(): void
    {
        $this->get('/users/create')
            ->assertOk()
            ->assertSee('Cadastro de Entidade')
            ->assertSee('name="name"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false)
            ->assertSee('name="summary"', false)
            ->assertSee('name="authority_level"', false)
            ->assertSee('value="civic"', false)
            ->assertSee('value="warrior"', false)
            ->assertSee('value="elder"', false);
    }

    public function test_show_page_displays_user_data_and_avatar_url(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'avatar_path' => 'avatars/profile.png',
            'summary' => 'Royal science and systems lead.',
            'authority_level' => 'warrior',
        ]);

        Storage::disk('public')->put('avatars/profile.png', 'avatar');

        $this->get("/users/{$user->id}")
            ->assertOk()
            ->assertSee('Resumo do arquivo')
            ->assertSee($user->name)
            ->assertSee($user->summary)
            ->assertSee($user->avatar_url, false);
    }

    public function test_edit_page_prefills_existing_data(): void
    {
        $user = User::factory()->create([
            'summary' => 'Royal science and systems lead.',
            'authority_level' => 'warrior',
        ]);

        $response = $this->get("/users/{$user->id}/edit");

        $response->assertOk()
            ->assertSee('Atualizar Cadastro')
            ->assertSee($user->name)
            ->assertSee($user->email)
            ->assertSee($user->summary)
            ->assertSee('name="summary"', false);

        $this->assertMatchesRegularExpression(
            '/name="authority_level"[^>]+value="warrior"[^>]+checked/',
            $response->getContent()
        );
    }
}
