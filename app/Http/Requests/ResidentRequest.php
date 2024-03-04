<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Constant\GlobalConstant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResidentRequest extends FormRequest
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
            'name' =>  'required',
            'status' =>  'required|in:permanent,contract',
            'phone' => 'required|numeric',
            'is_married' => 'required|numeric|in:0,1',
        ];

        if ($this->isMethod('POST')) {
            $rules['id_card_photo'] = 'required|mimes:jpeg,jpg,png|max:5000';
        } else {
            $rules['id_card_photo'] = 'mimes:jpeg,jpg,png|max:5000';
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi.',
            'phone.required' => 'Nomor HP harus diisi.',
            'is_married.required' => 'Pernikahan harus diisi.',
            'id_card_photo.required' => 'Foto KTP harus diisi.',
            'status.required' => 'Status harus diisi.'
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->error($validator->errors(), 400));
    }
}
