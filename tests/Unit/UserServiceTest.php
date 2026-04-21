<?php

namespace Tests\Unit;

use App\DTOs\UserData;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private function fakeImageUpload(string $name = 'avatar.png'): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'avatar_');
        file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8DwHwAFBQIAX8jx0gAAAABJRU5ErkJggg=='));

        return new UploadedFile($path, $name, 'image/png', null, true);
    }

    public function test_get_all_filters_users_by_profile_fields(): void
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

        $users = app(UserService::class)->getAll('Archive');

        $this->assertCount(1, $users);
        $this->assertSame('Alice Example', $users->first()->name);
    }

    public function test_create_persists_the_user_and_uploads_avatar(): void
    {
        Storage::fake('public');

        $user = app(UserService::class)->create(
            new UserData(
                name: 'Nakia Okoye',
                email: 'nakia@example.com',
                password: 'Secret123!',
                summary: 'Chief intelligence liaison for the archive.',
                authorityLevel: 'elder',
            ),
            $this->fakeImageUpload()
        );

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nakia Okoye',
            'email' => 'nakia@example.com',
            'summary' => 'Chief intelligence liaison for the archive.',
            'authority_level' => 'elder',
        ]);
        $this->assertTrue(Hash::check('Secret123!', $user->password));
        $this->assertNotNull($user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);
    }

    public function test_update_replaces_avatar_and_keeps_existing_password(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'password' => Hash::make('Secret123!'),
            'avatar_path' => 'avatars/old-avatar.png',
            'summary' => 'Original summary',
            'authority_level' => 'civic',
        ]);

        Storage::disk('public')->put('avatars/old-avatar.png', 'old-avatar');

        $updated = app(UserService::class)->update(
            $user->id,
            new UserData(
                name: 'Nakia Okoye',
                email: 'nakia@example.com',
                password: null,
                summary: 'Updated summary',
                authorityLevel: 'warrior',
            ),
            $this->fakeImageUpload('new-avatar.png')
        );

        $this->assertSame('Updated summary', $updated->summary);
        $this->assertSame('warrior', $updated->authority_level);
        $this->assertTrue(Hash::check('Secret123!', $updated->password));
        $this->assertNotSame('avatars/old-avatar.png', $updated->avatar_path);
        Storage::disk('public')->assertMissing('avatars/old-avatar.png');
        Storage::disk('public')->assertExists($updated->avatar_path);
    }

    public function test_delete_removes_the_user_and_avatar_file(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'avatar_path' => 'avatars/delete-me.png',
        ]);

        Storage::disk('public')->put('avatars/delete-me.png', 'avatar');

        $result = app(UserService::class)->delete($user->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
        Storage::disk('public')->assertMissing('avatars/delete-me.png');
    }
}
