<?php

declare(strict_types=1);

namespace Tests\Service\User;

use App\Dto\UpdateUserDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\User\UpdateUser;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    public function test_it_updates_user_name(): void
    {
        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);

        $updateUserDto = UpdateUserDto::fromArray([
            'name' => 'Test Name',
        ]);

        $user = new User;
        $user->id = rand(1, 99);
        $user->name = 'Default Name';

        $userRepositoryMock
            ->expects($this->once())
            ->method('update')
            ->with(
                $this->equalTo($user->id),
                $this->equalTo(['name' => 'Test Name'])
            );

        $updateUser = new UpdateUser($userRepositoryMock);
        $updateUser->update($updateUserDto, $user);

        $this->assertTrue(true);
    }
}
