<?php

declare(strict_types=1);

namespace Tests\Service\Core;

use App\Dto\ResetPasswordDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Core\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    public function test_it_reset_user_password(): void
    {
        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);

        $dto = ResetPasswordDto::fromArray([
            'password' => 'screetPassword123',
        ]);

        $user = new User;
        $user->id = rand(1, 99);

        $userRepositoryMock
            ->expects($this->once())
            ->method('update')
            ->with(
                $this->equalTo($user->id),
                $this->callback(function ($data) use ($dto) {
                    return Hash::check($dto->password, $data['password']);
                })
            );

        $service = new ResetPassword($userRepositoryMock);
        $service->reset($dto, $user);

        $this->assertTrue(true);
    }
}
