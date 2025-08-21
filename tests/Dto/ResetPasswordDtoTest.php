<?php

declare(strict_types=1);

namespace Tests\Dto;

use App\Dto\ResetPasswordDto;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ResetPasswordDtoTest extends TestCase
{
    public function test_it_creates_dto_with_valid_password(): void
    {
        $params = ['password' => 'secret123'];

        $dto = ResetPasswordDto::fromArray($params);

        $this->assertInstanceOf(ResetPasswordDto::class, $dto);
        $this->assertEquals('secret123', $dto->password);
    }

    public function test_it_fails_when_password_is_null(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field password a required.');

        ResetPasswordDto::fromArray(['password' => null]);
    }

    public function test_it_fails_when_password_is_too_short(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Must contain a minimum of 6 characters.');

        ResetPasswordDto::fromArray(['password' => '123']);
    }

    public function test_it_fails_when_password_is_not_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type. Expected string.');

        ResetPasswordDto::fromArray(['password' => 123456]);
    }
}
