<?php

declare(strict_types=1);

namespace Tests\Dto;

use App\Dto\UpdateUserDto;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UpdateUserDtoTest extends TestCase
{
    public function test_it_creates_dto_with_valid_data(): void
    {
        $params = ['name' => 'Teste Name'];

        $dto = UpdateUserDto::fromArray($params);

        $this->assertInstanceOf(UpdateUserDto::class, $dto);
        $this->assertEquals($params['name'], $dto->name);
    }

    public function test_it_fails_when_name_is_too_short(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Must contain a minimum of 4 characters.');

        UpdateUserDto::fromArray(['name' => 'Tes']);
    }

    public function test_it_fails_when_name_is_too_long(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Must contain a maximum of 200 characters.');

        UpdateUserDto::fromArray(['name' => str_repeat('a', 201)]);
    }

    public function test_it_fails_when_name_is_null(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field name a required.');

        UpdateUserDto::fromArray(['name' => null]);
    }

    public function test_it_fails_when_name_is_not_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type. Expected string.');

        UpdateUserDto::fromArray(['name' => 123456]);
    }
}
