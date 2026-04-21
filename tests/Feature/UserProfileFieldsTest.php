<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileFieldsTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_be_filtered_by_search_term(): void
    {
        $this->actingAs(User::factory()->create());

        User::factory()->create([
            'name' => 'Alice Example',
            'email' => 'alice@example.com',
            'summary' => 'Archive keeper',
            'authority_level' => 'civic',
            'rua' => 'Rua A',
            'bairro' => 'Centro',
            'cidade' => 'Cuiabá',
            'estado' => 'MT',
            'cep' => '78000000',
        ]);

        User::factory()->create([
            'name' => 'Bob Example',
            'email' => 'bob@example.com',
            'summary' => 'Field operator',
            'authority_level' => 'warrior',
            'rua' => 'Rua B',
            'bairro' => 'Araés',
            'cidade' => 'Cuiabá',
            'estado' => 'MT',
            'cep' => '78005000',
        ]);

        $this->get('/users?search=Alice')
            ->assertOk()
            ->assertSee('Alice Example')
            ->assertDontSee('Bob Example');
    }

    public function test_user_creation_persists_avatar_summary_and_authority(): void
    {
        Storage::fake('public');
        Http::fake([
            'viacep.com.br/ws/78000000/json/' => Http::response([
                'cep' => '78000-000',
                'logradouro' => 'Rua dos Testes',
                'complemento' => '',
                'unidade' => '',
                'bairro' => 'Centro',
                'localidade' => 'Cuiabá',
                'uf' => 'MT',
                'estado' => 'Mato Grosso',
                'regiao' => 'Centro-Oeste',
                'ibge' => '5103403',
                'gia' => '',
                'ddd' => '65',
                'siafi' => '9069',
            ]),
        ]);

        $response = $this->post('/users', [
            'name' => 'Nakia Okoye',
            'email' => 'nakia@example.com',
            'password' => 'password123',
            'summary' => 'Chief intelligence liaison for the archive.',
            'authority_level' => 'elder',
            'avatar' => $this->fakeImageUpload('avatar.png'),
            'cep' => '78000000',
        ]);

        $response->assertRedirect(route('login'));

        $user = User::query()->where('email', 'nakia@example.com')->firstOrFail();

        $this->assertSame('Chief intelligence liaison for the archive.', $user->summary);
        $this->assertSame('elder', $user->authority_level);
        $this->assertSame('Rua dos Testes', $user->rua);
        $this->assertSame('Centro', $user->bairro);
        $this->assertSame('Cuiabá', $user->cidade);
        $this->assertSame('MT', $user->estado);
        $this->assertSame('78000000', $user->cep);
        $this->assertNotNull($user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);

        $this->post('/login', [
            'email' => 'nakia@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('users.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_edit_screen_prefills_summary_and_authority_level(): void
    {
        $this->actingAs(User::factory()->create());

        $user = User::factory()->create([
            'name' => 'Shuri',
            'email' => 'shuri@example.com',
            'summary' => 'Royal science and systems lead.',
            'authority_level' => 'warrior',
            'rua' => 'Rua do Palácio',
            'bairro' => 'Centro',
            'cidade' => 'Cuiabá',
            'estado' => 'MT',
            'cep' => '78000000',
        ]);

        $response = $this->get("/users/{$user->id}/edit")
            ->assertOk()
            ->assertSee('Royal science and systems lead.')
            ->assertSee('name="summary"', false)
            ->assertSee('name="authority_level"', false)
            ->assertSee('value="warrior"', false);

        $this->assertMatchesRegularExpression(
            '/value="warrior"[^>]*checked\/>/',
            $response->getContent()
        );
    }

    private function fakeImageUpload(string $name = 'avatar.png'): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'avatar_');
        file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO2l8KQAAAAASUVORK5CYII='));

        return new UploadedFile($path, $name, 'image/png', null, true);
    }
}
