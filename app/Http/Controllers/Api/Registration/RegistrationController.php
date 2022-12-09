<?php

namespace App\Http\Controllers\Api\Registration;

use App\Http\Controllers\Api\ApiController;
use App\Http\Presenters\AuthUserResponseArrayPresenter;
use App\Http\Requests\Api\Auth\RegisterValidationRequest;
use App\Http\Responses\Api\Auth\AuthenticationResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class RegistrationController extends ApiController
{
    /**
     * @OA\Post(
     *      path="/auth/register",
     *      operationId="registerUser",
     *      tags={"Auth"},
     *      summary="Sing up",
     *      description="Register new user",
     *      @OA\RequestBody(
     *          required=true,
     *
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function __invoke(
        RegisterValidationRequest $request,
        AuthUserResponseArrayPresenter $authUserResponseArrayPresenter
    ):JsonResponse
    {
        $user = User::create([
             'name' => $request->get('name'),
             'email' => $request->get('email'),
             'password' => Hash::make($request->get('password')),
        ]);

        $token = Auth::login($user);

        $response = new AuthenticationResponse(
            $user,
            (string)$token,
            'bearer',
            auth()->factory()->getTTL() * 60
        );

        return $this->successResponse($authUserResponseArrayPresenter->present($response));
    }
}
