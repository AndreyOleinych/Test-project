<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Exceptions\InvalidResetPasswordTokenException;
use App\Exceptions\ResetPasswordThrottledException;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\ResetPasswordValidationRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends ApiController
{
    public function __invoke(
        ResetPasswordValidationRequest $request
    ): JsonResponse
    {
        $response = Password::broker()->reset([
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'password_confirmation' => $request->get('password_confirmation'),
                'token' => $request->get('token'),
            ],
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
                event(new PasswordReset($user));
        });

        if ($response == Password::INVALID_TOKEN || $response != Password::PASSWORD_RESET) {
            throw new InvalidResetPasswordTokenException();
        }

        if ($response != Password::RESET_THROTTLED) {
            throw new ResetPasswordThrottledException();
        }

        return $this->successResponse(['message' => __('passwords.reset')]);
    }
}
