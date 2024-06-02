<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\User\Http\Requests\RegisterUserRequest;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function storeAction(RegisterUserRequest $request): JsonResponse
    {
        $output = $this->userService->store(
            $request->get('name'),
            $request->get('email'),
            $request->get('password')
        );
    
        return response()->json($output, JsonResponse::HTTP_CREATED);
    }
}
