<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Constant\GlobalConstant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HouseholderRequest extends FormRequest
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
     * @return array<string => 'required', mixed>
     */
    public function rules()
    {
        $rules = [
            'house_id' =>  'required|numeric',
            'resident_id' =>  'nullable|numeric',
            'status' =>  'required|numeric|in:0,1',
        ];
        return $rules;
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status harus diisi.',
            'house_id.required' => 'Rumah harus diisi.',
            'resident_id.required' => 'Penghuni harus diisi.'
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->error($validator->errors(), 400));
    }
}
