<?php

namespace App\Services;

use App\DTOs\UserData;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function getAll(?string $search = null): Collection
    {
        return User::query()
            ->when(
                filled($search),
                fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('summary', 'like', '%'.$search.'%')
                        ->orWhere('authority_level', 'like', '%'.$search.'%');
                })
            )
            ->latest()
            ->get();
    }

    public function findById(int $id): User
    {
        return User::query()->findOrFail($id);
    }

    public function create(UserData $data, ?UploadedFile $avatar = null): User
    {
        $payload = $this->payloadFromData($data);

        if ($avatar !== null) {
            $payload['avatar_path'] = $avatar->store('avatars', 'public');
        }

        return User::create($payload);
    }

    public function update(int $id, UserData $data, ?UploadedFile $avatar = null): User
    {
        $user = $this->findById($id);

        $payload = $this->payloadFromData($data, false);
        $previousAvatarPath = $user->avatar_path;

        if ($avatar !== null) {
            $payload['avatar_path'] = $avatar->store('avatars', 'public');
        }

        $user->update($payload);

        if ($avatar !== null && $previousAvatarPath) {
            Storage::disk('public')->delete($previousAvatarPath);
        }

        return $user->refresh();
    }

    public function delete(int $id): bool
    {
        $user = $this->findById($id);

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        return $user->delete();
    }

    /**
     * @return array<string, string|null>
     */
    private function payloadFromData(UserData $data, bool $includePassword = true): array
    {
        $payload = [
            'name' => $data->name,
            'email' => $data->email,
            'summary' => $data->summary,
            'authority_level' => $data->authorityLevel,
        ];

        if ($includePassword) {
            $payload['password'] = $data->password;
        }

        return $payload;
    }
}
