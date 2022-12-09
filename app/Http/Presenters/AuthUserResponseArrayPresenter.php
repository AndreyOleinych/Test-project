<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Http\Responses\Api\Auth\AuthenticationResponse;

final class AuthUserResponseArrayPresenter
{
    public function __construct(private UserArrayPresenter $userArrayPresenter)
    {}

    public function present(AuthenticationResponse $response): array
    {
        return [
            'user' => $this->userArrayPresenter->present($response->getUser()),
            'token' => $response->getAccessToken(),
            'type' => $response->getTokenType(),
            'time' => $response->getExpiresIn()
        ];
    }
}
