<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\UpdateUserDto;
use App\Models\User;
use App\Services\User\UpdateUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        private readonly UpdateUser $updateUserService
    ) {}

    public function updateUser(Request $request): JsonResponse
    {
        $dto = UpdateUserDto::fromArray($request->all());

        /** @var User $user */
        $user = Auth::user();

        $this->updateUserService->update($dto, $user);

        return response()->json([
            'Profile updated successfully',
        ], Response::HTTP_ACCEPTED);
    }
}
