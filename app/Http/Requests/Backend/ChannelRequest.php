<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ChannelRequest extends FormRequest
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
            'channel_name' => 'required|max:200|min:3',
        ];
    }

    public function messages()
    {
        return [
            'channel_name.required' => 'กรุณากรอกชื่อช่องรายการทีวี',
            'channel_name.max' => 'ชื่อช่องรายการทีวี ไม่ควรเกิน 200 ตัวอักษร',
            'channel_name.min' => 'ชื่อช่องรายการทีวีต้องมีอย่างน้อย 3 ตัวอักษร'
        ];
    }
}
