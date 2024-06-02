<?php

namespace Modules\User\Rules;

use App\Rules\BaseRule;
use App\Services\Cryptography\JsonWebToken;
use Modules\User\Models\User;

class UserExistsRule extends BaseRule
{
    public function passes($attribute, $value): bool
    {
        $id = JsonWebToken::decode($value);
        $this->validateId($id);

        return User::where('id', $id)->exists();
    }

    public function message(): string
    {
        return trans('exception.not_found.user');
    }
}
