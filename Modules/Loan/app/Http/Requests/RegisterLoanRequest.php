<?php

namespace Modules\Loan\Http\Requests;

use App\Http\Requests\BaseRequest;
use Modules\Book\Rules\BookExistsRule;
use Modules\User\Rules\UserExistsRule;

class RegisterLoanRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string> */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'string', new UserExistsRule],
            'book_id' => ['required', 'string', new BookExistsRule]
        ];
    }
}
