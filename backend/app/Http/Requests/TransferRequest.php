<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from' => 'required|numeric|exists:bank_accounts,id|different:to',
            'to' => 'required|numeric|exists:bank_accounts,id|different:from',
            'category_id' => 'required|numeric|exists:categories,id',
            'due_at' => 'required|date',
            'amount' => 'required|numeric',
            'payed' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'from.numeric' => 'The :attribute field must be numeric.',
            'from.exists' => "This account doesn't exists.",
            'from.different' => "This fields must be different",

            'to.numeric' => 'The :attribute field must be numeric.',
            'to.exists' => "This account doesn't exists.",
            'to.different' => "This fields must be different",

            'category_id.numeric' => 'The :attribute field must be numeric.',
            'category_id.exists' => "This category doesn't exists.",

            'amount.numeric' => 'The :attribute field must be numeric.',
        ];
    }
    
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors], 422));
    }
}
