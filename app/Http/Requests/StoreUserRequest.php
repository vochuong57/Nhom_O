<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|string|email|unique:users|max:255|regex:/^[^<>&]*$/',
            'name'=>'required|min:2|max:100|string|regex:/^[^\d]+$/|regex:/^[^<>&]*$/',
            'user_catalogue_id'=> [
                'required',
                'integer',
                'gt:0',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('user_catalogue_id', 1);
                }),
            ],
            'password'=> 'required|string|min:5|max:10|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[^\w<>&]/|regex:/^(?!.*[<>&]).*$/',
            'repassword'=> 'required|same:password|regex:/^(?!.*[<>&]).*$/',

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
            'email.unique'=>'Email đã tồn tại. Hãy chọn email khác',
            'email.max'=>'Độ dài email tối đa 100 ký tự',
            'email.regex'=>'Email không được chứa ký tự <, >, &',
            'name.required'=>'Bạn chưa nhập họ tên',
            'name.string'=>'Tên phải là dạng ký tự',
            'name.regex'=>'Tên không được chứa ký tự số',
            'user_catalogue_id'=>'Bạn chưa chọn nhóm thành viên',
            'user_catalogue_id.unique'=>'Nhóm quản trị viên này đã có người đãm nhận',
            'password.required'=>'Bạn chưa nhập vào mật khẩu mới hoặc bạn đang nhập ký tự khoảng trắng',
            'password.string'=>'Mật khẩu phải là dạng ký tự',
            'password.min'=>'Độ dài mật khẩu mới tối thiểu 5 ký tự',
            'password.max'=>'Độ dài mật khẩu mới tối đa 10 ký tự',
            'password.regex'=>'Mật khẩu mới không được chứa ký tự <, >, &, có ít nhật 1 chữ thường, 1 chữ HOA và 1 chữ số cũng như 1 ký tự đặc biệt',
            'repassword.required'=>'Bạn chưa nhập lại mật khẩu mới hoặc bạn đang nhập ký tự khoảng trắng',
            'repassword.regex'=>'Mật khẩu mới không được chứa ký tự <, >, &, có ít nhật 1 chữ thường, 1 chữ HOA và 1 chữ số cũng như 1 ký tự đặc biệt',
            'repassword.same'=>'Mật khẩu nhập lại không khớp',
            'phone.required'=>'Bạn chưa nhập số điện thoại',
            'phone.regex'=>'Số điện không hợp lệ vui lòng nhập theo định dạng: 0xxxxxxxxx',
            'address.regex'=>'Địa chỉ không được chứa ký tự <, >, &',
            'description.regex'=>'Ghi chú không được chứa ký tự <, >, &',
        ];
    }
}
