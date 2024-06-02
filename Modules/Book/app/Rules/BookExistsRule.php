<?php

namespace Modules\Book\Rules;

use App\Rules\BaseRule;
use App\Services\Cryptography\JsonWebToken;
use Modules\Book\Models\Book;

class BookExistsRule extends BaseRule
{
    public function passes($attribute, $value): bool
    {
        $id = JsonWebToken::decode($value);
        $this->validateId($id);

        return Book::where('id', $id)->exists();
    }

    public function message(): string
    {
        return trans('exception.not_found.book');
    }
}
