@include('Backend.dashboard.component.breadcrumb', ['title' =>$config['seo']['title']])
<!-- từ khóa tìm kiếm/validation/Displaying the Validation Errors -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@php
    $url=($config['method']=='create')?route('user.create'):route('user.update', $user->id)
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>- Nhập thông tin chung của người sử dụng</p>
                        <p>- Lưu ý: những trường đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Email: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="email"
                                    value="{{ old('email', ($user->email)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Họ tên: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="name"
                                    value="{{ old('name', ($userInfo->name)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nhóm thành viên: <span class="text-danger">(*)</span></label>
                                    <select name="user_catalogue_id" id="" class="form-control setupSelect2">
                                        <option value="0">[Chọn nhóm thành viên]</option>
                                        @foreach($userCatalogues as $userCatalogue)
                                            <option 
                                                {{ $userCatalogue->id == old('user_catalogue_id', (isset($user->user_catalogue_id)) ? $user->user_catalogue_id : '') ? 'selected' : '' }} 
                                                value="{{ $userCatalogue->id }}"
                                            >
                                                {{ $userCatalogue->name }}
                                            </option>
                                        @endforeach
                                    </select>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ngày sinh: </label>
                                    <div class="form-date datepicker">
                                        <input 
                                        type="text"
                                        name="birthday"
                                        value="{{ old('birthday', !empty($userInfo->birthday) ? \Carbon\Carbon::parse($userInfo->birthday)->format('d/m/Y') : '') }}"
                                        class="form-control datepicker"
                                        placeholder="dd/mm/yyyy"
                                        autocomplete="off"
                                        readonly
                                        >
                                        <span class="span-icon-calendar"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($config['method']=='create')
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Mật khẩu: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="password"
                                    name="password"
                                    value=""
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nhập lại mật khẩu: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="password"
                                    name="repassword"
                                    value=""
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ảnh đại diện: </label>
                                    <input 
                                    type="text"
                                    name="image"
                                    value="{{ old('image', ($userInfo->image)??'') }}"
                                    class="form-control upload-image"
                                    placeholder=""
                                    autocomplete="off"
                                    data-type="Images"
                                    readonly
                                    >
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin liên hệ</div>
                    <div class="panel-description">
                        <p>- Nhập thông tin liên hệ của người sử dụng</p>
                        <p>- Lưu ý: những trường đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin liên hệ</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Thành phố: </label>
                                    <select name="province_id" id="" class="form-control setupSelect2 provinces location" data-target="DTdistricts">
                                        <option value="0">[Chọn thành phố]</option>
                                        @if(isset($provinces))
                                        @foreach($provinces as $province)
                                        <option value="{{ $province->code }}">{{ $province->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Quận/Huyện: </label>
                                    <select name="district_id" id="" class="form-control setupSelect2 districts DTdistricts location" data-target="DTwards">
                                        <option value="0">[Chọn Quận/Huyện]</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Phường/Xã: </label>
                                    <select name="ward_id" id="" class="form-control setupSelect2 wards DTwards">
                                        <option value="0">[Chọn Phường/Xã]</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Địa chỉ: </label>
                                    <input 
                                    type="text"
                                    name="address"
                                    value="{{ old('address', ($userInfo->address)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Số điện thoại: <span class="text-danger">(*)</span></label>
                                    <input 
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', ($userInfo->phone)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ghi chú: </label>
                                    <input 
                                    type="text"
                                    name="description"
                                    value="{{ old('description', ($userInfo->description)??'') }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">{{ $config['seo']['btnTitle'] }}</button>
        </div>
    </div>
</form>
<script>
    var getLocationUrl = '{{ route("ajax.location.getLocation") }}';
    var province_id='{{ (isset($userInfo->province_id)) ? $userInfo->province_id : old('province_id') }}'
    var district_id='{{ (isset($userInfo->district_id)) ? $userInfo->district_id : old('district_id') }}'
    var ward_id='{{ (isset($userInfo->ward_id)) ? $userInfo->ward_id : old('ward_id') }}'
</script>