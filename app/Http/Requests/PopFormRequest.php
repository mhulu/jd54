<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PopFormRequest extends FormRequest
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
            'address' => 'required',
            'birthday' => ['required', 'date_format:Y-m-d'],
            'identify' => ['required', 'regex:/^\d{6}(18|19|20)\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)$/', 'unique:pops,identify'],
            'livetype' => 'required',
            'name' => 'required',
            'nation' => 'required',
            'paytype' => 'required',
            'phone' => 'required',
            'sex' => 'required'
        ];
    }
}
