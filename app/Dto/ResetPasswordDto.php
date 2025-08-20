<?php

declare(strict_types=1);

namespace App\Dto;

readonly class ResetPasswordDto
{
    private function __construct(
        public readonly string $password
    ) {}

    public static function fromArray(array $validated): self
    {
        return new self(
            password: (string) $validated['password']
        );
    }
}
