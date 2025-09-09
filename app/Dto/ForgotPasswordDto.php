<?php

declare(strict_types=1);

namespace App\Dto;

use Assert\Assert;

readonly class ForgotPasswordDto
{
    private function __construct(
        public readonly string $email,
        public readonly ?string $code,
        public readonly ?string $password
    ) {}

    private static function validate(array $params): void
    {
        Assert::that($params['email'])
            ->notNull('Field email a required.')
            ->string('Invalid type. Expected string.')
            ->email('Not a valid email format.');
    }

    public static function fromArray(array $params): self
    {
        self::validate($params);

        return new self(
            email: (string) $params['email'],
            code: $params['code'] ?? null,
            password: $params['password'] ?? null
        );
    }
}
