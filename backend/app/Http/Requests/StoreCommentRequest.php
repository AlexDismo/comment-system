<?php

namespace App\Http\Requests;

use App\Rules\ValidXhtml;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'string',
            'content' => ['required', new ValidXhtml],
            'author_name' => 'required|string',
            'author_email' => 'required|email',
            'author_url' => 'nullable|url',
            'parent_id' => 'nullable|integer',
            'files[].*' => ['mimes:gif,jpg,png,txt', function ($attribute, $value, $fail) {
                if ($value->getClientOriginalExtension() === 'txt' && $value->getSize() / 1024 > 100) {
                    $fail('The txt file may not be greater than 100 kilobytes.');
                }
            }],
        ];
    }

    public function messages(): array
    {
        return [
            'files.*.mimes' => 'The file must be a file of type: gif, jpg, png, txt.',
            'files.*' => 'The txt file may not be greater than 100 kilobytes.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
