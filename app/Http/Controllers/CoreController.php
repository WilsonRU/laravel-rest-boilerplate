<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\ForgotPasswordDto;
use App\Dto\LoginDto;
use App\Dto\ResetPasswordDto;
use App\Dto\SaveUserDto;
use App\Models\User;
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

    /**
     * @OA\Post(
     *     path="/api/core/login",
     *     summary="Login",
     *     description="Access token and user data",
     *     tags={"Core"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email","password"},
     *
     *             @OA\Property(property="email", type="string", example="test@example.com"),
     *             @OA\Property(property="password", type="string", example="secret@123")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login successful, returns access token and user data",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImlhdCI6MTUxNjIzOTAyMn0.KMUFsIDTnFmyG3nMiGM6H9FNFUROf3wh7SmqJp-QV30"
     *             ),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Test"),
     *                 @OA\Property(property="email", type="string", example="test@example.com")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $dto = LoginDto::fromArray($request->all());

        if (Auth::attempt($dto->toArray())) {
            $dataResponse = $this->loginService->login($dto);

            return response()->json($dataResponse, 200);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @OA\Post(
     *     path="/api/core/signup",
     *     summary="Signup",
     *     description="Create a user account.",
     *     tags={"Core"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name"},
     *             required={"email"},
     *             required={"password"},
     *
     *             @OA\Property(property="name", type="string", example="Test"),
     *             @OA\Property(property="email", type="string", example="test@example.com"),
     *             @OA\Property(property="password", type="string", example="secret@123")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="User created successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'value' for key 'email'"
     *     )
     * )
     */
    public function signup(Request $request): JsonResponse
    {
        $dto = SaveUserDto::fromArray($request->all());
        $this->saveUserService->save($dto);

        return response()->json([
            'message' => 'User created successfully',
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/api/core/forgot-password",
     *     summary="Forgot Password",
     *     description="Request password reset. Sends a recovery link to the provided email.",
     *     tags={"Core"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email"},
     *
     *             @OA\Property(property="email", type="string", example="test@example.com")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=202,
     *         description="Reset email sent! Check your inbox for the password reset link.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Reset email sent! Check your inbox for the password reset link.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="No account found with this email. Please check or sign up."
     *     )
     * )
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $dto = ForgotPasswordDto::fromArray($request->all());

        if ($dto->password === null && $dto->code === null) {
            $this->forgotPasswordService->sendCode($dto);

            return response()->json([
                'message' => 'Reset email sent! Check your inbox for the password reset link.',
            ], Response::HTTP_ACCEPTED);
        }

        if ($dto->code !== null) {
            $this->forgotPasswordService->validateCode($dto);

            return response()->json([], Response::HTTP_ACCEPTED);
        }

        $this->forgotPasswordService->validateCode($dto);

        return response()->json([
            'message' => 'Password changed successfully',
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Post(
     *     path="/api/core/reset-password",
     *     summary="Reset Password",
     *     description="Update only the password by the ID taken by the token",
     *     security={{"sanctum":{}}},
     *     tags={"Core"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"password"},
     *
     *             @OA\Property(property="password", type="string", example="secret@123")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=202,
     *         description="Password changed successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Password changed successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found."
     *     )
     * )
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $dto = ResetPasswordDto::fromArray($request->all());

        /** @var User $user */
        $user = Auth::user();
        $this->resetPasswordService->reset($dto, $user);

        return response()->json([
            'message' => 'Password changed successfully',
        ], Response::HTTP_ACCEPTED);
    }
}
