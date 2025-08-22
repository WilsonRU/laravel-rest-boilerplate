<?php

declare(strict_types=1);

namespace Tests\Unit\Dto;

use App\Dto\LoginDto;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LoginDtoTest extends TestCase
{
    public function test_it_creates_dto_with_valid_data(): void
    {
        $params = [
            'email' => 'user@example.com',
            'password' => 'secret123',
        ];

        $dto = LoginDto::fromArray($params);

        $this->assertInstanceOf(LoginDto::class, $dto);
        $this->assertEquals('user@example.com', $dto->email);
        $this->assertEquals('secret123', $dto->password);
        $this->assertEquals($params, $dto->toArray());
    }

    public function test_it_fails_when_email_is_null(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field email a required.');

        LoginDto::fromArray(['email' => null, 'password' => 'secret123']);
    }

    public function test_it_fails_when_email_is_invalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Not a valid email format.');

        LoginDto::fromArray(['email' => 'invalid-email', 'password' => 'secret123']);
    }

    public function test_it_fails_when_email_is_not_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type. Expected string.');

        LoginDto::fromArray(['email' => 12345, 'password' => 'secret123']);
    }

    public function test_it_fails_when_password_is_null(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field password a required.');

        LoginDto::fromArray(['email' => 'user@example.com', 'password' => null]);
    }

    public function test_it_fails_when_password_is_too_short(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Must contain a minimum of 6 characters.');

        LoginDto::fromArray(['email' => 'user@example.com', 'password' => '123']);
    }

    public function test_it_fails_when_password_is_not_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type. Expected string.');

        LoginDto::fromArray(['email' => 'user@example.com', 'password' => 123456]);
    }
}
