<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\UserServiceInterface as UserService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Repositories\Interfaces\UserInfoRepositoryInterface as UserInfoRepository;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userInfoRepository;
    protected $userService;
    protected $userRepository;
    protected $provinceRepository;

    public function __construct(UserService $userService, UserRepository $userRepository,ProvinceRepository $provinceRepository,UserInfoRepository $userInfoRepository){
        $this->userService=$userService;
        $this->userRepository=$userRepository;
        $this->userInfoRepository=$userInfoRepository;
        $this->provinceRepository=$provinceRepository;
    }
    public function index(Request $request){
        $rules = [
            'keyword' => [
                'nullable',
                'regex:/^[^<>&]*$/'
            ], 
        ];
        $messages = [
            'keyword.regex' => 'Trường này không được chứa các ký tự đặc biệt như <, >, &.'
        ];
        $request->validate($rules, $messages);

        $config=$this->configIndex();

        $template='Backend.user.user.index';

        $config['seo']=config('apps.User.index');

        $users = $this->userService->paginate($request);

        $stt = 1;
        foreach($users as $user){
            $user->stt = $stt++;
        }

        $userCatalogues = DB::table('user_catalogues')
        ->select('id', 'name', 'description')
        ->orderBy('id', 'asc')
        ->get();
                
        foreach ($users as $user) {
            $user->encrypted_id = $this->encryptId($user->id);
        }

        return view('Backend.dashboard.layout', compact('template','config','users','userCatalogues'));
    }

    public function store(){   
        $template='Backend.user.user.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.User.create');

        $config['method']='create';

        $userCatalogues = DB::table('user_catalogues')
        ->select('id', 'name', 'description')
        ->orderBy('id', 'asc')
        ->get();

        $provinces=$this->provinceRepository->all();

        return view('Backend.dashboard.layout', compact('template','config','userCatalogues', 'provinces'));
    }

    public function create(StoreUserRequest $request){
        if($this->userService->createUser($request)){
            return redirect()->route('user.index')->with('success','Thêm mới thành viên thành công');
        }
           return redirect()->route('user.index')->with('error','Thêm mới thành viên thất bại. Hãy thử lại');
        
    }
    public function edit($id){
        $template='Backend.user.user.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.User.edit');

        $config['method']='edit';

        $id = $this->decryptId($id);

        if (!preg_match('/^[0-9A-Za-z=]+$/', $id)) {
            return redirect()->route('user.index')->withErrors('ID không hợp lệ. Vui lòng sử dụng ID đã mã hóa.');
        }

        $userCatalogues = DB::table('user_catalogues')
        ->select('id', 'name', 'description')
        ->orderBy('id', 'asc')
        ->get();

        $provinces=$this->provinceRepository->all();

        $user=$this->userRepository->findById($id);

        $condition=[
            ['user_id', '=', $id]
        ];

        $userInfo=$this->userInfoRepository->findByCondition($condition);

        $id_logged = Auth::id();
       
        $user_logged=$this->userRepository->findById($id_logged);

        if ($userInfo->user_catalogue_id == 1  && $user_logged->user_catalogue_id != 1) {
            return redirect()->route('user.index')->with('error', 'Thành viên '.$userInfo->name.' thuộc nhóm quản trị viên không thể sửa.');
        }

        return view('Backend.dashboard.layout', compact('template','config','user','userCatalogues', 'userInfo', 'provinces'));
    }
    public function update($id, UpdateUserRequest $request){
        
        if($this->userService->updateUser($id, $request)){
            return redirect()->route('user.index')->with('success','Cập nhật thành viên thành công');
        }
           return redirect()->route('user.index')->with('error','Cập nhật thành viên thất bại. Hãy thử lại');
    }
    public function destroy($id){
        $template='Backend.user.user.destroy';

        $config=$this->configCUD();

        $config['seo']=config('apps.User.delete');

        $id = $this->decryptId($id);

        if (!preg_match('/^[0-9A-Za-z=]+$/', $id)) {
            return redirect()->route('user..index')->withErrors('ID không hợp lệ. Vui lòng sử dụng ID đã mã hóa.');
        }

        $user=$this->userRepository->findById($id);

        $condition=[
            ['user_id', '=', $id]
        ];

        $userInfo=$this->userInfoRepository->findByCondition($condition);

        if ($id == 1) {
            return redirect()->route('user.index')->with('error', 'Thành viên này thuộc nhóm quản trị viên không thể xóa.');
        }

        return view('Backend.dashboard.layout', compact('template','config','user','userInfo'));
    }
    public function delete($id){
        if($this->userService->deleteUser($id)){
            return redirect()->route('user.index')->with('success','Xóa thành viên thành công');
        }
           return redirect()->route('user.index')->with('error','Xóa thànhviên thất bại. Hãy thử lại');
    }
    
    public function updatePermission(Request $request){
        if($this->userService->setPermission($request)){
            return redirect()->route('user..index')->with('success','Cập nhật quyền thành viên thành công');
        }
        return redirect()->route('user..index')->with('error','Cập nhật quyền thành viên thất bại. Hãy thử lại');
    }
    private function configIndex(){
        return[
           'js'=>[
                'Backend/vendor/jquery/jquery.min.js',
                'Backend/vendor/bootstrap/js/bootstrap.bundle.min.js',
                'Backend/vendor/jquery-easing/jquery.easing.min.js',
                'Backend/js/sb-admin-2.min.js',
                'Backend/vendor/chart.js/Chart.min.js',
                'Backend/js/demo/chart-area-demo.js',
                'Backend/js/demo/chart-pie-demo.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'Backend/libary/libary.js',
                'Backend/libary/location.js',
                'Backend/plugins/ckfinder/ckfinder.js',
                'Backend/libary/finder.js',
                'Backend/plugins/datetimepicker-master/build/jquery.datetimepicker.full.js',
                'Backend/js/plugins/switchery/switchery.js',
            ],
            'css'=>[
                'Backend/vendor/fontawesome-free/css/all.min.css',
                'https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i',
                'Backend/css/sb-admin-2.min.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'Backend/plugins/datetimepicker-master/build/jquery.datetimepicker.min.css',
                'Backend/css/plugins/switchery/switchery.css',
            ],
            'model'=>'User'
        ];
    }

    private function configCUD(){
        return[
            'js'=>[
                'Backend/vendor/jquery/jquery.min.js',
                'Backend/vendor/bootstrap/js/bootstrap.bundle.min.js',
                'Backend/vendor/jquery-easing/jquery.easing.min.js',
                'Backend/js/sb-admin-2.min.js',
                'Backend/vendor/chart.js/Chart.min.js',
                'Backend/js/demo/chart-area-demo.js',
                'Backend/js/demo/chart-pie-demo.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'Backend/libary/libary.js',
                'Backend/libary/libary.js',
                'Backend/libary/location.js',
                'Backend/plugins/ckfinder/ckfinder.js',
                'Backend/libary/finder.js',
                'Backend/plugins/datetimepicker-master/build/jquery.datetimepicker.full.js',
            ],
            'css'=>[
                'Backend/vendor/fontawesome-free/css/all.min.css',
                'https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i',
                'Backend/css/sb-admin-2.min.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'Backend/plugins/datetimepicker-master/build/jquery.datetimepicker.min.css',
            ]
        ];
    }

}
