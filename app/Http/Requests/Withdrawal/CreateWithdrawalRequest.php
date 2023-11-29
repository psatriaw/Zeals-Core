<?php

namespace App\Http\Requests\Withdrawal;

use Illuminate\Foundation\Http\FormRequest;

class CreateWithdrawalRequest extends FormRequest
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
            "total_pencairan"           => ['required','min:50000','numeric'],
            "nomor_rekening"            => ['required'],
            "nama_pemilik_rekening"     => ['required'],
        ];
    }

    public function messages(){
        return [
            "total_pencairan.required" => "Please fill this field!",
            "total_pencairan.min"      => "Minimum amount of withdrawal is IDR 50K", 
            "total_pencairan.numeric"  => "Please fill with numeric only",
            "nomor_rekening.required"           => "Please fill this field! You can change it from your <a href=".url('profile')." class='text-white'>[ profile ] </a>",
            "nama_pemilik_rekening.required"    => "Please fill this field! You can change it from your <a href=".url('profile')." class='text-white'>[ profile ]</a>",
        ];
    }
}
