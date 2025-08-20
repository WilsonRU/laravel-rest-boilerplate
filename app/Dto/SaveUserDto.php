<?php

declare(strict_types=1);

namespace App\Dto;

readonly class SaveUserDto
{
    private function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    ) {}

    public static function fromArray(array $validated): self
    {
        return new self(
            name: (string) $validated['name'],
            email: (string) $validated['email'],
            password: (string) $validated['password']
        );
    }
}
