<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'role' => 'required|in:Member,Admin',
            'username' => 'required|unique:users|max:50',
            'first_name' => 'max:125',
            'last_name' => 'max:125',
            'password' => 'required|confirmed|min:6',
        ];
        // 'email' => 'required|email|unique:users|max:200',
    }

    public function messages()
    {
        return [
            'username.required' => 'กรุณากรอกชื่อผู้ใช้งาน',
            'username.unique' => 'ชื่อผู้ใช้งานนี้ มีอยู่ในระบบแล้ว',
            'username.max' => 'ชื่อผู้ใช้งาน ไม่ควรเกิน 50 ตัวอักษร',
            'first_name.max' => 'ชื่อจริง ไม่ควรเกิน 125 ตัวอักษร',
            'last_name.max' => 'นามสกุล ไม่ควรเกิน 125 ตัวอักษร',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.confirmed' => 'การยืนยันรหัสผ่านไม่ตรงกัน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร'
        ];
        // 'email.required' => 'กรุณากรอกอีเมล์',
        // 'email.unique' => 'อีเมล์นี้ มีอยู่ในระบบแล้ว',
        // 'email.max' => 'อีเมล์ ไม่ควรเกิน 200 ตัวอักษร',
    }
}
