<?php

declare(strict_types=1);

namespace App\Services\Core;

use App\Dto\ResetPasswordDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPassword
{
    public function reset(ResetPasswordDto $resetPasswordDto, User $user): void
    {
        $user->update([
            'password' => Hash::make($resetPasswordDto->password),
        ]);
    }
}
