<?php

namespace Modules\Book\Http\Requests;

use App\Http\Requests\BaseRequest;
use Modules\Book\Rules\BookExistsRule;

class DestroyBookRequest extends BaseRequest
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
            'id' => ['required', 'string', new BookExistsRule],
        ]);

        if ($validator->fails()) {
            $this->failedValidation($validator);
        }
    }
}
