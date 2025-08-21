<?php

declare(strict_types=1);

namespace App\Dto;

use Assert\Assert;

readonly class UpdateUserDto
{
    private function __construct(
        public readonly string $name
    ) {}

    private static function validate(array $params): void
    {
        Assert::that($params['name'])
            ->notNull('Field name a required.')
            ->string('Invalid type. Expected string.')
            ->minLength(4, 'Must contain a minimum of 4 characters.')
            ->maxLength(200, 'Must contain a maximum of 200 characters.');
    }

    public static function fromArray(array $params): self
    {
        self::validate($params);

        return new self(
            name: (string) $params['name']
        );
    }
}
