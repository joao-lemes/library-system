<?php

namespace Modules\User\Services;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use Modules\User\Transformers\OutputLogin;
use Modules\User\Transformers\OutputLogout;
use Modules\User\Transformers\OutputUser;

class AuthService
{
    /** @param array<string> $credentials */
    public function login(array $credentials): OutputLogin
    {
        if (!$token = auth()->attempt($credentials)) {
            throw new UnauthorizedException();
        }

        return new OutputLogin($token);
    }

    public function logout(): OutputLogout
    {
        auth()->logout();

        return new OutputLogout(true);
    }

    public function getAuthenticatedUser(): OutputUser
    {
        if (!$user = auth()->user()) {
            throw new NotFoundException(trans('exception.not_found.user'));
        }

        return new OutputUser($user);
    }
}
