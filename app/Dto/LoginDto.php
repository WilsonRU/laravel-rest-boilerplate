<?php

declare(strict_types=1);

namespace App\Dto;

use Assert\Assert;

readonly class LoginDto
{
    private function __construct(
        public readonly string $email,
        public readonly string $password
    ) {}

    private static function validate(array $params): void
    {
        Assert::that($params['email'])
            ->notNull('Field email a required.')
            ->email('Not a valid email format.')
            ->string('Invalid type. Expected string.');

        Assert::that($params['password'])
            ->notNull('Field password a required.')
            ->minLength(6, 'Must contain a minimum of 6 characters.')
            ->string('Invalid type. Expected string.');
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    public static function fromArray(array $params): self
    {
        self::validate($params);

        return new self(
            email: (string) $params['email'],
            password: (string) $params['password']
        );
    }
}
