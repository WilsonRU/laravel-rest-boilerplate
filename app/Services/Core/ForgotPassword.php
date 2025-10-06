<?php

declare(strict_types=1);

namespace App\Services\Core;

use App\Dto\ForgotPasswordDto;
use App\Repositories\Contracts\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPassword
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function sendCode(ForgotPasswordDto $forgotPasswordDto): void
    {
        $user = $this->userRepository->byEmail($forgotPasswordDto->email);

        $uniqCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put($user->getAttribute('email'), $uniqCode, now()->addMinutes(10));

        Mail::raw("Recovery code: $uniqCode", function ($message) use ($user) {
            $message->to($user->getAttribute('email'))
                ->subject('Forgot Password - Recovery Code');
        });
    }

    public function validateCode(ForgotPasswordDto $forgotPasswordDto): void
    {
        $user = $this->userRepository->byEmail($forgotPasswordDto->email);

        $uniqCode = Cache::get($user->getAttribute('email'));
        if ($uniqCode && $uniqCode === $forgotPasswordDto->code) {
            $this->userRepository->update($user->getAttribute('id'), [
                'password' => Hash::make((string) $forgotPasswordDto->password),
            ]);

            return;
        }

        throw new Exception('Invalid Verification Code');
    }
}
