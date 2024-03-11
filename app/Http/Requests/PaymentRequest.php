<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Constant\GlobalConstant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
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
            'payment_type_id' =>  'required|numeric',
        ];
        return $rules;
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'payment_type_id.required' => 'Jenis pembayaran harus diisi.',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->error($validator->errors(), 400));
    }
}
