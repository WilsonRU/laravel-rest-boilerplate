<?php

declare(strict_types=1);

namespace App\Services\Core;

use App\Dto\ForgotPasswordDto;
use App\Repositories\Contracts\UserRepositoryInterface;

class ForgotPassword
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function forgot(ForgotPasswordDto $forgotPasswordDto): void
    {
        $user = $this->userRepository->byEmail($forgotPasswordDto->email);
    }
}
