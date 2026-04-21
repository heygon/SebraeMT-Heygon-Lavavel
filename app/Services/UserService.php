<?php

namespace App\Services;

use App\DTOs\UserData;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function __construct(
        protected CepLookupService $cepLookupService
    ) {
    }

    public function getAll(?string $search = null): Collection
    {
        return User::query()
            ->when(
                filled($search),
                fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('summary', 'like', '%'.$search.'%')
                        ->orWhere('authority_level', 'like', '%'.$search.'%')
                        ->orWhere('rua', 'like', '%'.$search.'%')
                        ->orWhere('bairro', 'like', '%'.$search.'%')
                        ->orWhere('cidade', 'like', '%'.$search.'%')
                        ->orWhere('estado', 'like', '%'.$search.'%')
                        ->orWhere('cep', 'like', '%'.$search.'%');
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
        $address = $this->resolveAddressFromData($data);

        $payload = [
            'name' => $data->name,
            'email' => $data->email,
            'summary' => $data->summary,
            'authority_level' => $data->authorityLevel,
            'rua' => $address['rua'],
            'bairro' => $address['bairro'],
            'cidade' => $address['cidade'],
            'estado' => $address['estado'],
            'cep' => $address['cep'],
        ];

        if ($includePassword) {
            $payload['password'] = $data->password;
        }

        return $payload;
    }

    /**
     * @return array{rua:?string,bairro:?string,cidade:?string,estado:?string,cep:?string}
     */
    private function resolveAddressFromData(UserData $data): array
    {
        $cep = preg_replace('/\D+/', '', (string) $data->cep) ?: null;

        if ($cep === null) {
            return [
                'rua' => $data->rua,
                'bairro' => $data->bairro,
                'cidade' => $data->cidade,
                'estado' => $data->estado,
                'cep' => null,
            ];
        }

        $resolved = null;

        if (blank($data->rua) || blank($data->bairro) || blank($data->cidade) || blank($data->estado)) {
            $resolved = $this->cepLookupService->lookup($cep);
        }

        return [
            'rua' => filled($data->rua) ? $data->rua : ($resolved['rua'] ?? null),
            'bairro' => filled($data->bairro) ? $data->bairro : ($resolved['bairro'] ?? null),
            'cidade' => filled($data->cidade) ? $data->cidade : ($resolved['cidade'] ?? null),
            'estado' => filled($data->estado) ? strtoupper($data->estado) : ($resolved['estado'] ?? null),
            'cep' => $cep,
        ];
    }
}
