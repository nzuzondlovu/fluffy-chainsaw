<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchExchangeRateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'base_symbol' => 'required|string',
            'end_date' => 'required|date|after:start_date',
            'start_date' => 'required|date|before:end_date',
        ];
    }
}
