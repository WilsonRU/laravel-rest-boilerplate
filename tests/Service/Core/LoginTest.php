<?php

declare(strict_types=1);

namespace Tests\Service\Core;

use App\Dto\LoginDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Core\Login;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class Logintest extends TestCase
{
    public function test_it_logs_in_successfully(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);

        $loginDto = LoginDto::fromArray([
            'email' => 'test@example.com',
            'password' => 'secret',
        ]);

        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('getAttribute')
            ->with('password')
            ->willReturn(Hash::make('secret'));

        $user->expects($this->once())
            ->method('createToken')
            ->with('user-token', [])
            ->willReturn((object) ['plainTextToken' => 'fake-token']);

        $userRepository->expects($this->once())
            ->method('byEmail')
            ->with($loginDto->email)
            ->willReturn($user);

        $login = new Login($userRepository);

        $result = $login->login($loginDto);

        $this->assertEquals('fake-token', $result['token']);
        $this->assertSame($user, $result['user']);
    }

    public function test_it_throws_exception_if_user_not_found(): void
    {
        $this->expectException(ValidationException::class);

        $userRepository = $this->createMock(UserRepositoryInterface::class);

        $loginDto = LoginDto::fromArray([
            'email' => 'test@example.com',
            'password' => 'secret',
        ]);

        $userRepository->expects($this->once())
            ->method('byEmail')
            ->willReturn(null);

        $login = new Login($userRepository);

        $login->login($loginDto);
    }

    public function test_it_throws_exception_if_password_is_wrong(): void
    {
        $this->expectException(ValidationException::class);

        $userRepository = $this->createMock(UserRepositoryInterface::class);

        $loginDto = LoginDto::fromArray([
            'email' => 'test@example.com',
            'password' => 'wrong123456',
        ]);

        $user = $this->createMock(User::class);
        $user->method('getAttribute')->with('password')->willReturn(Hash::make('secret'));

        $userRepository->method('byEmail')->willReturn($user);

        $login = new Login($userRepository);

        $login->login($loginDto);
    }
}
