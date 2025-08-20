<?php

declare(strict_types=1);

namespace App\Dto;

readonly class LoginDto
{
    private function __construct(
        public readonly string $email,
        public readonly string $password
    ) {}

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password
        ];
    }

    public static function fromArray(array $validated): self
    {
        return new self(
            email: (string) $validated['email'],
            password: (string) $validated['password']
        );
    }
}
