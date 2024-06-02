<?php

namespace Modules\Book\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Modules\Author\Rules\AuthorExistsRule;

class RegisterBookRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string> */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('books')->whereNull('deleted_at'),
            ],
            'year_of_publication' => 'required | date_format:Y',
            'author_ids' => 'required | array',
            'author_ids.*' => ['required', 'string', new AuthorExistsRule]
        ];
    }
}
