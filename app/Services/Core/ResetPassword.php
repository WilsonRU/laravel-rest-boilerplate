<?php

declare(strict_types=1);

namespace App\Services\Core;

use App\Dto\ResetPasswordDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ResetPassword
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function reset(ResetPasswordDto $resetPasswordDto, User $user): void
    {
        $this->userRepository->update($user->getAttribute('id'), [
            'password' => Hash::make($resetPasswordDto->password),
        ]);
    }
}
