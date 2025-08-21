<?php

declare(strict_types=1);

namespace App\Dto;

use Assert\Assert;

readonly class ResetPasswordDto
{
    private function __construct(
        public readonly string $password
    ) {}

    private static function validate(array $params): void
    {
        Assert::that($params['password'])
            ->notNull('Field password a required.')
            ->minLength(6, 'Must contain a minimum of 6 characters.')
            ->string('Invalid type. Expected string.');
    }

    public static function fromArray(array $params): self
    {
        self::validate($params);

        return new self(
            password: (string) $params['password']
        );
    }
}
