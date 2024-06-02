<?php

namespace Modules\Book\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Modules\Author\Rules\AuthorExistsRule;
use Modules\Book\Rules\BookExistsRule;

class UpdateBookRequest extends BaseRequest
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
                'sometimes',
                'string',
                'max:255',
                Rule::unique('books')->whereNull('deleted_at'),
            ],
            'year_of_publication' => 'sometimes | date_format:Y',
            'author_ids' => 'sometimes | array',
            'author_ids.*' => ['required', 'string', new AuthorExistsRule]
        ];
    }

    public function validateResolved(): void
    {
        parent::validateResolved();
        
        $validator = \Illuminate\Support\Facades\Validator::make([
            'id' => $this->id
        ], [
            'id' => ['required', 'string', new BookExistsRule],
        ]);

        if ($validator->fails()) {
            $this->failedValidation($validator);
        }
    }
}
