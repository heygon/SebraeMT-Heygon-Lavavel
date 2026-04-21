<?php

namespace App\DTOs;

readonly class UserData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
        public ?string $summary = null,
        public ?string $authorityLevel = null,
        public ?string $rua = null,
        public ?string $bairro = null,
        public ?string $cidade = null,
        public ?string $estado = null,
        public ?string $cep = null,
    ) {
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password'] ?? null,
            summary: $validated['summary'] ?? null,
            authorityLevel: $validated['authority_level'] ?? null,
            rua: $validated['rua'] ?? null,
            bairro: $validated['bairro'] ?? null,
            cidade: $validated['cidade'] ?? null,
            estado: $validated['estado'] ?? null,
            cep: $validated['cep'] ?? null,
        );
    }
}
