<?php

declare(strict_types=1);

namespace App\Dto;

use Assert\Assert;

readonly class SaveUserDto
{
    private function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    ) {}

    private static function validate(array $params): void
    {
        Assert::that($params['name'])
            ->notNull('Field name a required.')
            ->string('Invalid type. Expected string.');

        Assert::that($params['email'])
            ->notNull('Field email a required.')
            ->string('Invalid type. Expected string.')
            ->email('Not a valid email format.');

        Assert::that($params['password'])
            ->notNull('Field password a required.')
            ->string('Invalid type. Expected string.')
            ->minLength(6, 'Must contain a minimum of 6 characters.');
    }

    public static function fromArray(array $params): self
    {
        self::validate($params);

        return new self(
            name: (string) $params['name'],
            email: (string) $params['email'],
            password: (string) $params['password']
        );
    }
}
