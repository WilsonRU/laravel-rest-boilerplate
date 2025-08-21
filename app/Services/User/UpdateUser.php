<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Dto\UpdateUserDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UpdateUser
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function update(UpdateUserDto $updateUserDto, User $user): void
    {
        $this->userRepository->update($user->getAttribute('id'), [
            'name' => $updateUserDto->name,
        ]);
    }
}
