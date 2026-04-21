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
        );
    }
}
