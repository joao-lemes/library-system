<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Hash;
use Modules\User\Repositories\UserRepository;
use Modules\User\Transformers\OutputUserCollection;
use Modules\User\Transformers\OutputUserRegister;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(): OutputUserCollection
    {
        return new OutputUserCollection($this->userRepository->all());
    }

    public function store(
        string $name,
        string $email,
        string $password
    ): OutputUserRegister {
        $user = $this->userRepository->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $token = JWTAuth::fromUser($user);

        return new OutputUserRegister(compact('user', 'token'));
    }
}
