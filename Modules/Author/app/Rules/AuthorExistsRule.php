<?php

namespace Modules\Author\Rules;

use App\Rules\BaseRule;
use App\Services\Cryptography\JsonWebToken;
use Modules\Author\Models\Author;

class AuthorExistsRule extends BaseRule
{
    public function passes($attribute, $value): bool
    {
        $id = JsonWebToken::decode($value);
        $this->validateId($id);

        return Author::where('id', $id)->exists();
    }

    public function message(): string
    {
        return trans('exception.not_found.author');
    }
}
