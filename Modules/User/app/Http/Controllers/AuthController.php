<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function loginAction(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $output = $this->authService->login($credentials);

        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function logoutAction(): JsonResponse
    {
        $output = $this->authService->logout();

        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function getAuthenticatedUserAction(): JsonResponse
    {
        $output = $this->authService->getAuthenticatedUser();

        return response()->json($output);
    }
}
