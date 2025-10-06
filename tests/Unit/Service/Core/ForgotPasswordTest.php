<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Core;

use App\Dto\ForgotPasswordDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Core\ForgotPassword;
use Exception;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    public function test_it_send_recovery_code(): void
    {
        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);

        $dto = ForgotPasswordDto::fromArray([
            'email' => 'test@laravel.com',
        ]);

        $user = new User;
        $user->id = rand(1, 99);
        $user->email = $dto->email;

        $userRepositoryMock
            ->expects($this->once())
            ->method('byEmail')
            ->with($dto->email)
            ->willReturn($user);

        $forgotPassword = new ForgotPassword($userRepositoryMock);
        $forgotPassword->sendCode($dto);
    }

    public function test_it_send_recovery_code_user_notfound(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The requested user could not be found');

        $dto = ForgotPasswordDto::fromArray([
            'email' => 'test@laravel.com',
        ]);

        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);

        $userRepositoryMock
            ->expects($this->once())
            ->method('byEmail')
            ->with($dto->email)
            ->will($this->throwException(new Exception('The requested user could not be found')));

        $forgotPassword = new ForgotPassword($userRepositoryMock);
        $forgotPassword->sendCode($dto);
    }

    public function test_it_updates_password_when_code_is_valid(): void
    {
        $dto = ForgotPasswordDto::fromArray([
            'email' => 'test@laravel.com',
            'code' => '123456',
            'password' => 'newpassword',
        ]);

        $user = new User;
        $user->email = 'test@laravel.com';
        $user->id = 1;

        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $userRepositoryMock->method('byEmail')->willReturn($user);
        $userRepositoryMock->expects($this->once())
            ->method('update')
            ->with($user->id, $this->callback(function ($arg) {
                return isset($arg['password']) && password_verify('newpassword', $arg['password']);
            }));

        Cache::shouldReceive('get')
            ->once()
            ->with('test@laravel.com')
            ->andReturn('123456');

        $service = new ForgotPassword($userRepositoryMock);
        $service->validateCode($dto);
    }

    public function test_it_throws_exception_when_code_is_invalid(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid Verification Code');

        $dto = ForgotPasswordDto::fromArray([
            'email' => 'test@laravel.com',
            'code' => 'wrongcode',
            'password' => 'newpassword',
        ]);

        $user = new User;
        $user->email = 'test@laravel.com';
        $user->id = 1;

        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $userRepositoryMock->method('byEmail')->willReturn($user);

        Cache::shouldReceive('get')
            ->once()
            ->with('test@laravel.com')
            ->andReturn('123456');

        $service = new ForgotPassword($userRepositoryMock);
        $service->validateCode($dto);
    }
}
