<?php

declare(strict_types=1);

namespace Tests\Unit\Dto;

use App\Dto\ForgotPasswordDto;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ForgotPasswordDtoTest extends TestCase
{
    public function test_it_creates_dto_with_valid_email(): void
    {
        $params = ['email' => 'user@example.com'];

        $dto = ForgotPasswordDto::fromArray($params);

        $this->assertInstanceOf(ForgotPasswordDto::class, $dto);
        $this->assertEquals('user@example.com', $dto->email);
    }

    public function test_it_fails_when_email_is_null(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field email a required.');

        ForgotPasswordDto::fromArray(['email' => null]);
    }

    public function test_it_fails_when_email_is_invalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Not a valid email format.');

        ForgotPasswordDto::fromArray(['email' => 'invalid-email']);
    }

    public function test_it_fails_when_email_is_not_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type. Expected string.');

        ForgotPasswordDto::fromArray(['email' => 12345]);
    }
}
