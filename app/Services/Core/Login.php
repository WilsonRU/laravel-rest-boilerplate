<?php

declare(strict_types=1);

namespace App\Services\Core;

use App\Dto\LoginDto;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Login
{
    private function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function login(LoginDto $loginDto): array
    {
        $user = $this->userRepository->byEmail($loginDto->email);

        if (! $user || ! Hash::check($loginDto->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect'],
            ]);
        }

        return [
            'token' => $user->createToken('user-token', [])->plainTextToken,
            'user' => $user,
        ];
    }
}
