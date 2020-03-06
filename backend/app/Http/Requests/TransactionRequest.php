<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'description' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required|in:Income,Expense',
            'due_at' => 'required|date',
            'category_id' => 'required|numeric|exists:categories,id',
            'account_id' => 'required|numeric|exists:bank_accounts,id',
            'payed' => 'required|boolean',
        ];
    }
}
