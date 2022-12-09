<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Exceptions\FailedSentPasswordResetLinkException;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\SendResetPasswordLinkValidationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends ApiController
{
    public function __invoke(
        SendResetPasswordLinkValidationRequest $request
    ): JsonResponse
    {
        $response = Password::broker()->sendResetLink(['email' => $request->get('email')]);

        if ($response != Password::RESET_LINK_SENT){
            throw new FailedSentPasswordResetLinkException();
        }

        return $this->successResponse(['message' => __('passwords.sent')]);
    }
}
