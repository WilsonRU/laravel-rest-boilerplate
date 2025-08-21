<?php

declare(strict_types=1);

namespace App\Services\Core;

use App\Dto\SaveUserDto;
use App\Repositories\Contracts\UserRepositoryInterface;
use DateTime;
use Illuminate\Support\Facades\Hash;

class SaveUser
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function save(SaveUserDto $saveUserDto): void
    {
        $this->userRepository->create([
            'name' => $saveUserDto->name,
            'email' => $saveUserDto->email,
            'password' => Hash::make($saveUserDto->password),
            'email_verified_at' => new DateTime('now'),
        ]);
    }
}
