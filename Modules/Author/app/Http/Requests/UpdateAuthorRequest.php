<?php

namespace Modules\Author\Http\Requests;

use App\Http\Requests\BaseRequest;
use Carbon\Carbon;
use Modules\Author\Rules\AuthorExistsRule;

class UpdateAuthorRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string> */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'birth_date' => [
                'sometimes',
                'date',
                'before_or_equal:' . Carbon::now()->subYears(10)->format('Y-m-d'),
            ],
        ];
    }

    public function validateResolved(): void
    {
        parent::validateResolved();
        
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
