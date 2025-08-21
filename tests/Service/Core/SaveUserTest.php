<?php

declare(strict_types=1);

namespace Tests\Service\Core;

use App\Dto\SaveUserDto;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Core\SaveUser;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SaveUserTest extends TestCase
{
    public function test_it_create_user(): void
    {
        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);

        $dto = SaveUserDto::fromArray([
            'name' => 'Test',
            'email' => 'teste@example.com',
            'password' => 'screetPassword123',
        ]);

        $userRepositoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($data) use ($dto) {
                return $data['name'] === $dto->name
                    && $data['email'] === $dto->email
                    && Hash::check($dto->password, $data['password'])
                    && $data['email_verified_at'] instanceof \DateTime;
            }));

        $service = new SaveUser($userRepositoryMock);
        $service->save($dto);

        $this->assertTrue(true);
    }
}
