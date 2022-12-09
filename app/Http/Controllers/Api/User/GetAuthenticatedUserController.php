<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiController;
use App\Http\Presenters\UserArrayPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetAuthenticatedUserController extends ApiController
{
    /**
     * Get User Info.
     *
     * @param  UserArrayPresenter  $presenter
     *
     * @return JsonResponse
     */
    public function __invoke(UserArrayPresenter $presenter): JsonResponse
    {
        $user = Auth::user();

        return $this->successResponse($presenter->present($user));
    }
}
