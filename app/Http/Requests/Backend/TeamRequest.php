<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
            'team_name_th' => 'required|max:200|min:3',
            'team_name_en' => 'required|max:200|min:3',
        ];
    }

    public function messages()
    {
        return [
            'team_name_th.required' => 'กรุณากรอกชื่อทีม ภาษาไทย',
            'team_name_th.max' => 'ชื่อทีม ไม่ควรเกิน 200 ตัวอักษร',
            'team_name_th.min' => 'ชื่อทีมต้องมีอย่างน้อย 3 ตัวอักษร',
            'team_name_en.required' => 'กรุณากรอกชื่อทีม ภาษาอังกฤษ',
            'team_name_en.max' => 'ชื่อทีม ไม่ควรเกิน 200 ตัวอักษร',
            'team_name_en.min' => 'ชื่อทีมต้องมีอย่างน้อย 3 ตัวอักษร'
        ];
    }
}
