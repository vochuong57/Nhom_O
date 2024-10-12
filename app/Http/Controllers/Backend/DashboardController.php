<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\UserInfoRepositoryInterface as UserInfoRepository;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Services\Interfaces\UserServiceInterface as UserService;

class DashboardController extends Controller
{
    protected $userInfoRepository;
    protected $userCatalogueRepository;
    protected $provinceRepository;
    protected $userRepository;
    protected $userService;

    public function __construct(UserInfoRepository $userInfoRepository, UserCatalogueRepository $userCatalogueRepository,ProvinceRepository $provinceRepository,UserRepository $userRepository,UserService $userService){
        $this->userInfoRepository=$userInfoRepository;
        $this->userCatalogueRepository=$userCatalogueRepository;
        $this->provinceRepository=$provinceRepository;
        $this->userRepository=$userRepository;
        $this->userService=$userService;
    }

    public function index(){
        $config=$this->config();
        $template='Backend.home.index';
        return view('Backend.dashboard.layout', compact('template','config'));
    }

    public function edit(){
        $template='Backend.dashboard.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.userProfile.edit');

        $provinces=$this->provinceRepository->all();

        $id = Auth::id();
       
        $user=$this->userRepository->findById($id);

        $userCatalogues=$this->userCatalogueRepository->all();

        $condition=[
            ['user_id', '=', $id]
        ];

        $userInfo=$this->userInfoRepository->findByCondition($condition);

        return view('Backend.dashboard.layout', compact('template','config','provinces','user', 'userCatalogues','userInfo'));
    }

    public function update(UpdateUserProfileRequest $request){
        // dd($request);
        $id = Auth::id();
       
        if($this->userService->updateUser($id, $request)){
            return redirect()->route('dashboard.index')->with('success','Cập nhật thành viên thành công');
        }
           return redirect()->route('dashboard.index')->with('error','Cập nhật thành viên thất bại. Hãy thử lại');
    }

    private function config(){
        return [
            'js'=>[
                'Backend/vendor/jquery/jquery.min.js',
                'Backend/vendor/bootstrap/js/bootstrap.bundle.min.js',
                'Backend/vendor/jquery-easing/jquery.easing.min.js',
                'Backend/js/sb-admin-2.min.js',
                'Backend/vendor/chart.js/Chart.min.js',
                'Backend/js/demo/chart-area-demo.js',
                'Backend/js/demo/chart-pie-demo.js',
            ],
            'css'=>[
                'Backend/vendor/fontawesome-free/css/all.min.css',
                'https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i',
                'Backend/css/sb-admin-2.min.css',
            ],
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
