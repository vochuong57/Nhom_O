<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
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
            'name'=>'required|string|min:2|max:100|regex:/^[^<>&]*$/',
            'canonical'=>'required|unique:permissions,canonical, '.$this->id.'|string|min:3|max:100|regex:/^[^<>&]*$/'
        ];
    }

    public function messages(): array
    {
        return [
          
            'name.required'=>'Bạn chưa nhập tên quyền',
            'name.string'=>'Tên quyền phải là dạng ký tự',
            'name.regex'=>'Tên quyền không được chứa các ký tự <>&',
            'name.min'=>'Tên quyền phải nhập tổi thiểu từ 2 ký tự trở lên',
            'name.max'=>'Tên quyền không được nhập quá 100 ký tự',
            'canonical.required'=>'Bạn chưa nhập canonical',
            'canonical.unique'=>'Canonical này đã tồn tại hãy nhập lại canonical khác',
            'canonical.string'=>'Canonical phải là dạng ký tự',
            'canonical.regex'=>'Tên đường dẫn không được chứa các ký tự <>&',
            'canonical.min'=>'Tên đường dẫn phải nhập tổi thiểu từ 3 ký tự trở lên',
            'canonical.max'=>'Tên đường dẫn không được nhập quá 100 ký tự',
        ];
    }
}
