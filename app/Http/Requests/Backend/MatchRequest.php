<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class MatchRequest extends FormRequest
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
            'match_name' => 'required|max:200',
            'home_team' => 'required|max:125',
            'away_team' => 'required|max:125',
        ];
    }

    public function messages()
    {
        return [
            'match_name.required' => 'กรุณาใส่ชื่อแมทช์',
            'match_name.max' => 'แมทช์ ไม่ควรเกิน 200 ตัวอักษร',
            'home_team.required' => 'กรุณาใส่ชื่อทีมเหย้า',
            'home_team.max' => 'ทีมเหย้า ไม่ควรเกิน 125 ตัวอักษร',
            'away_team.required' => 'กรุณาใส่ชื่อทีมเยือน',
            'away_team.max' => 'ทีมเยือน ไม่ควรเกิน 125 ตัวอักษร',
        ];
    }
}
