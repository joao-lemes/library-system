<?php

namespace Modules\Book\Http\Requests;

use App\Http\Requests\BaseRequest;

class ShowBookRequest extends BaseRequest
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
        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $validator = \Illuminate\Support\Facades\Validator::make([
            'id' => $this->id
        ], [
            'id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $this->failedValidation($validator);
        }
    }
}
