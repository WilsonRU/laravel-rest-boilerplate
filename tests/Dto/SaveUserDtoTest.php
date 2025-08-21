<?php

declare(strict_types=1);

namespace Tests\Dto;

use App\Dto\SaveUserDto;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SaveUserDtoTest extends TestCase
{
    public function test_it_creates_dto_with_valid_data(): void
    {
        $params = [
            'name' => 'test user',
            'email' => 'test@example.com',
            'password' => 'secret123',
        ];

        $dto = SaveUserDto::fromArray($params);

        $this->assertInstanceOf(SaveUserDto::class, $dto);
        $this->assertEquals('test user', $dto->name);
        $this->assertEquals('test@example.com', $dto->email);
        $this->assertEquals('secret123', $dto->password);
    }

    public function test_it_fails_when_name_is_null(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field name a required.');

        SaveUserDto::fromArray([
            'name' => null,
            'email' => 'test@example.com',
            'password' => 'secret123',
        ]);
    }

    public function test_it_fails_when_name_is_not_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type. Expected string.');

        SaveUserDto::fromArray([
            'name' => 123,
            'email' => 'test@example.com',
            'password' => 'secret123',
        ]);
    }

    public function test_it_fails_when_email_is_null(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field email a required.');

        SaveUserDto::fromArray([
            'name' => 'test user',
            'email' => null,
            'password' => 'secret123',
        ]);
    }

    public function test_it_fails_when_email_is_invalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Not a valid email format.');

        SaveUserDto::fromArray([
            'name' => 'test user',
            'email' => 'invalid-email',
            'password' => 'secret123',
        ]);
    }

    public function test_it_fails_when_email_is_not_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type. Expected string.');

        SaveUserDto::fromArray([
            'name' => 'test user',
            'email' => 12345,
            'password' => 'secret123',
        ]);
    }

    public function test_it_fails_when_password_is_null(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field password a required.');

        SaveUserDto::fromArray([
            'name' => 'test user',
            'email' => 'test@example.com',
            'password' => null,
        ]);
    }

    public function test_it_fails_when_password_is_too_short(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Must contain a minimum of 6 characters.');

        SaveUserDto::fromArray([
            'name' => 'test user',
            'email' => 'test@example.com',
            'password' => '123',
        ]);
    }

    public function test_it_fails_when_password_is_not_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type. Expected string.');

        SaveUserDto::fromArray([
            'name' => 'test user',
            'email' => 'test@example.com',
            'password' => 123456,
        ]);
    }
}
