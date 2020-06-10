<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudent extends FormRequest
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
        $type = ['နိုင်', 'ဧည့်', 'ပြု', 'ယာယီ', 'စီ', 'သ'];
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'urn' => ['digits:6', 'unique:students'],
            'nrc_region' => ['required', 'regex:/.*[၁-၉]/', 'between:1,2'],
            'nrc_township' => ['required', 'regex:/.*[က-အ]/', 'size:3'],
            'nrc_type' => ['required', Rule::in($type)],
            'nrc_number' => ['required', 'regex:/.*[၀-၉]/', 'size:6'],
            'father_name' => ['required', 'string'],
            'batch' => ['required', 'digits:4'],
            'phone' => ['required', 'digits_between:8,11', 'unique:students']
        ];
    }
}
