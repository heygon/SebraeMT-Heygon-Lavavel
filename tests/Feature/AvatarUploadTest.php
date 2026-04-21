<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    private function createPngImage(): string
    {
        // Simple 1x1 red PNG
        return base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8DwHwAFBQIAX8jx0gAAAABJRU5ErkJggg==');
    }

    public function test_avatar_uploaded_on_user_creation(): void
    {
        Storage::fake('public');

        $tmpFile = tmpfile();
        $tmpPath = stream_get_meta_data($tmpFile)['uri'];
        fwrite($tmpFile, $this->createPngImage());
        rewind($tmpFile);

        $image = new UploadedFile($tmpPath, 'avatar.png', 'image/png', null, true);

        $response = $this->post('/users', [
            'name' => 'Avatar Test User',
            'email' => 'avatar@test.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'authority_level' => 'civic',
            'summary' => 'Test',
            'avatar' => $image,
        ]);

        $response->assertRedirect();

        $user = User::where('email', 'avatar@test.com')->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);
        $this->assertStringContainsString('avatars/', $user->avatar_path);
    }

    public function test_avatar_url_generated_correctly(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'avatar_path' => 'avatars/test.jpg',
        ]);

        $this->assertNotNull($user->avatar_url);
        $this->assertStringContainsString('storage', $user->avatar_url);
        $this->assertStringContainsString('avatars/test.jpg', $user->avatar_url);
    }

    public function test_avatar_replaced_on_user_update(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'avatar_path' => 'avatars/old.jpg',
        ]);

        Storage::disk('public')->put('avatars/old.jpg', 'old content');

        $tmpFile = tmpfile();
        $tmpPath = stream_get_meta_data($tmpFile)['uri'];
        fwrite($tmpFile, $this->createPngImage());
        rewind($tmpFile);

        $image = new UploadedFile($tmpPath, 'new.png', 'image/png', null, true);

        $response = $this->put("/users/{$user->id}", [
            'name' => $user->name,
            'email' => $user->email,
            'authority_level' => 'warrior',
            'summary' => 'Updated',
            'avatar' => $image,
        ]);

        $response->assertRedirect();

        $user->refresh();
        $this->assertNotNull($user->avatar_path);
        $this->assertNotEquals('avatars/old.jpg', $user->avatar_path);
        Storage::disk('public')->assertMissing('avatars/old.jpg');
    }

    public function test_edit_form_displays_avatar_url(): void
    {
        $user = User::factory()->create([
            'avatar_path' => 'avatars/test.jpg',
            'summary' => 'Test Summary',
            'authority_level' => 'elder',
        ]);

        $response = $this->get("/users/{$user->id}/edit");
        $response->assertStatus(200);
        $response->assertViewHas('user', $user);
    }
}
