<?php

namespace Modules\Author\Http\Requests;

use App\Http\Requests\BaseRequest;
use Modules\Author\Rules\AuthorExistsRule;

class DestroyAuthorRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string> */
    public function rules(): array
    {
        return [];
    }

    public function validateResolved(): void
    {        
        $validator = \Illuminate\Support\Facades\Validator::make([
            'id' => $this->id
        ], [
            'id' => ['required', 'string', new AuthorExistsRule],
        ]);

        if ($validator->fails()) {
            $this->failedValidation($validator);
        }
    }
}
