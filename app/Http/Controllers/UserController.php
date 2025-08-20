<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateUser(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|min:4|max:200',
        ]);

        /** @var App\Models\User $user */
        $user = Auth::user();

        $user->update([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'Profile updated successfully',
        ], Response::HTTP_ACCEPTED);
    }
}
