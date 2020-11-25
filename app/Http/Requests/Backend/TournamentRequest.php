<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class TournamentRequest extends FormRequest
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
            'tournament_name' => 'required|max:200|min:3',
        ];
    }

    public function messages()
    {
        return [
            'tournament_name.required' => 'กรุณากรอกชื่อทัวร์นาเม้นท์',
            'tournament_name.max' => 'ชื่อทัวร์นาเม้นท์ ไม่ควรเกิน 200 ตัวอักษร',
            'tournament_name.min' => 'ชื่อทัวร์นาเม้นท์ต้องมีอย่างน้อย 3 ตัวอักษร'
        ];
    }
}
