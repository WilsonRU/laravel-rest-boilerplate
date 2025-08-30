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

    /**
     * @OA\Put(
     *     path="/api/user/",
     *     summary="Update user name",
     *     description="Update only the username by the ID taken by the token",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name"},
     *
     *             @OA\Property(property="name", type="string", example="Test Name")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Profile updated successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found."
     *     )
     * )
     */
    public function updateUser(Request $request): JsonResponse
    {
        $dto = UpdateUserDto::fromArray($request->all());

        /** @var User $user */
        $user = Auth::user();

        $this->updateUserService->update($dto, $user);

        return response()->json([
            'message' => 'Profile updated successfully',
        ], Response::HTTP_ACCEPTED);
    }
}
