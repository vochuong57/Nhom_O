<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserCatalogueRequest extends FormRequest
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
            'name'=>'required|string|min:2|max:80|regex:/^[^<>&]*$/',
            'description'=>'nullable|regex:/^[^<>&]*$/',
            'publish'=>'gt:0'
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'=>'Bạn chưa nhập tên nhóm',
            'name.string'=>'Tên nhóm phải là dạng ký tự',
            'name.regex'=>'Tên nhóm không được chứa các ký tự <>&',
            'name.min'=>'Tên nhóm phải nhập tổi thiểu từ 2 ký tự trở lên',
            'name.max'=>'Tên nhóm không được nhập quá 80 ký tự',
            'description'=>'Ghi chú không được chứa các ký tự <>&',
            'publish.gt'=>'Bạn chưa chọn tình trạng nhóm'
        ];
    }
}
