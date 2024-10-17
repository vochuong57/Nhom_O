<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255|unique:users,email, '.$this->id.'|regex:/^[^<>&]*$/',
            'name'=>'required|min:2|max:100|string|regex:/^[^\d]+$/|regex:/^[^<>&]*$/',
            'user_catalogue_id'=> [
                'required',
                'integer',
                'gt:0',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('user_catalogue_id', 1);
                }),
            ],
            'phone'=>'required|string|regex:/^0[0-9]{9}$/|regex:/^[^<>&]*$/',
            'address'=>'nullable|regex:/^[^<>&]*$/',
            'description'=>'nullable|regex:/^[^<>&]*$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'=>'Bạn chưa nhập họ tên',
            'name.min'=>'Tên phải nhập tổi thiểu từ 2 ký tự trở lên',
            'name.max'=>'Tên không được nhập quá 80 ký tự',
            'name.string'=>'Tên phải là dạng ký tự',
            'name.regex'=>'Tên không được chứa ký tự số và không được chứa ký tự <, >, &',
            'email.required'=>'Bạn chưa nhập email.',
            'email.email'=>'Email chưa đúng định dạng. VD: abc@gmail.com',
            'email.string'=>'Email phải là dạng ký tự',
            'email.max'=>'Độ dài email tối đa 100 ký tự',
            'email.regex'=>'Email không được chứa ký tự <, >, &',
            'email.unique'=>'Email đã tồn tại. Hãy chọn email khác',
            'name.required'=>'Bạn chưa nhập họ tên',
            'name.string'=>'Tên phải là dạng ký tự',
            'name.regex'=>'Tên không được chứa ký tự số',
            'user_catalogue_id'=>'Bạn chưa chọn nhóm thành viên',
            'user_catalogue_id.unique'=>'Nhóm quản trị viên này đã có người đãm nhận',
            'phone.required'=>'Bạn chưa nhập số điện thoại',
            'phone.regex'=>'Số điện không hợp lệ vui lòng nhập theo định dạng: 0xxxxxxxxx',
            'address.regex'=>'Địa chỉ không được chứa ký tự <, >, &',
            'description.regex'=>'Ghi chú không được chứa ký tự <, >, &',
        ];
    }
}
