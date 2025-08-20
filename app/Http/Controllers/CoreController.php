<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\ForgotPasswordDto;
use App\Dto\LoginDto;
use App\Dto\ResetPasswordDto;
use App\Dto\SaveUserDto;
use App\Services\Core\ForgotPassword;
use App\Services\Core\Login;
use App\Services\Core\ResetPassword;
use App\Services\Core\SaveUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CoreController extends Controller
{
    public function __construct(
        protected readonly Login $loginService,
        protected readonly SaveUser $saveUserService,
        protected readonly ForgotPassword $forgotPasswordService,
        protected readonly ResetPassword $resetPasswordService
    ) {}

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:6',
        ]);

        $dto = LoginDto::fromArray($validated);

        if (Auth::attempt($dto->toArray())) {
            $dataResponse = $this->loginService->login($dto);

            return response()->json($dataResponse, 200);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function signup(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $dto = SaveUserDto::fromArray($validated);
        $this->saveUserService->save($dto);

        return response()->json([
            'message' => 'User created successfully',
        ], Response::HTTP_CREATED);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email:rfc,dns',
        ]);

        $dto = ForgotPasswordDto::fromArray($validated);

        $this->forgotPasswordService->forgot($dto);

        return response()->json([
            'message' => 'Reset email sent! Check your inbox for the password reset link.',
        ], Response::HTTP_ACCEPTED);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'password' => 'required|min:6',
        ]);

        $dto = ResetPasswordDto::fromArray($validated);
        $this->resetPasswordService->reset($dto, Auth::user());

        return response()->json([
            'message' => 'Password changed successfully',
        ], Response::HTTP_CREATED);
    }
}
