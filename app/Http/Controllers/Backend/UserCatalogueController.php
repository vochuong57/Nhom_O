<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\UserCatalogueServiceInterface as UserCatalogueService;
use App\Http\Requests\StoreUserCatalogueRequest;
use App\Http\Requests\UpdateUserCatalogueRequest;
use App\Http\Requests\DeleteUserCatalogueRequest;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;

class UserCatalogueController extends Controller
{
    protected $userCatalogueService;
    protected $userCatalogueRepository;

    public function __construct(UserCatalogueService $userCatalogueService, UserCatalogueRepository $userCatalogueRepository){
        $this->userCatalogueService=$userCatalogueService;
        $this->userCatalogueRepository=$userCatalogueRepository;
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

        $template='Backend.user.catalogue.index';

        $config['seo']=config('apps.UserCatalogue.index');

        $config['controllerName']="Catalogue";

        $userCatalogues = $this->userCatalogueService->paginate($request);
        
        foreach ($userCatalogues as $userCatalogue) {
            $userCatalogue->encrypted_id = $this->encryptId($userCatalogue->id);
        }

        return view('Backend.dashboard.layout', compact('template','config','userCatalogues'));
    }

    public function store(){   
        $template='Backend.user.catalogue.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.UserCatalogue.create');

        $config['method']='create';

        return view('Backend.dashboard.layout', compact('template','config'));
    }

    public function create(StoreUserCatalogueRequest $request){
        if($this->userCatalogueService->createUserCatalogue($request)){
            return redirect()->route('user.catalogue.index')->with('success','Thêm mới nhóm thành viên thành công');
        }
           return redirect()->route('user.catalogue.index')->with('error','Thêm mới nhóm thành viên thất bại. Hãy thử lại');
        
    }
    public function edit($id){
        $template='Backend.user.catalogue.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.UserCatalogue.edit');

        $config['method']='edit';

        $id = $this->decryptId($id);

        if (!preg_match('/^[0-9A-Za-z=]+$/', $id)) {
            return redirect()->route('user.catalogue.index')->withErrors('ID không hợp lệ. Vui lòng sử dụng ID đã mã hóa.');
        }

        $userCatalogue=$this->userCatalogueRepository->findById($id);

        return view('Backend.dashboard.layout', compact('template','config','userCatalogue'));
    }
    public function update($id, UpdateUserCatalogueRequest $request){
        
        if($this->userCatalogueService->updateUserCatalogue($id, $request)){
            return redirect()->route('user.catalogue.index')->with('success','Cập nhật nhóm thành viên thành công');
        }
           return redirect()->route('user.catalogue.index')->with('error','Cập nhật nhóm thành viên thất bại. Hãy thử lại');
    }
    public function destroy($id){
        $template='Backend.user.catalogue.destroy';

        $config=$this->configCUD();

        $config['seo']=config('apps.UserCatalogue.delete');

        $id = $this->decryptId($id);

        if (!preg_match('/^[0-9A-Za-z=]+$/', $id)) {
            return redirect()->route('user.catalogue.index')->withErrors('ID không hợp lệ. Vui lòng sử dụng ID đã mã hóa.');
        }

        $userCatalogue=$this->userCatalogueRepository->findById($id);

        if ($id == 1) {
            return redirect()->route('user.catalogue.index')->with('error', 'Đây là nhóm quản trị viên không thể xóa.');
        }elseif($id == 10){
            return redirect()->route('user.catalogue.index')->with('error', 'Đây là nhóm khách hàng không thể xóa.');
        }elseif($id == 9){
            return redirect()->route('user.catalogue.index')->with('error', 'Đây là nhóm tác giả không thể xóa.');
        }

        return view('Backend.dashboard.layout', compact('template','config','userCatalogue'));
    }
    public function delete(DeleteUserCatalogueRequest $request, $id){
        if ($request->hasUsers()) {
            return redirect()->route('user.catalogue.index')->with('error', 'Không thể xóa nhóm thành viên vì còn thành viên trong nhóm.');
        }
        if($this->userCatalogueService->deleteUserCatalogue($id)){
            return redirect()->route('user.catalogue.index')->with('success','Xóa nhóm thành viên thành công');
        }
           return redirect()->route('user.catalogue.index')->with('error','Xóa nhóm thành viên thất bại. Hãy thử lại');
    }
    
    public function updatePermission(Request $request){
        if($this->userCatalogueService->setPermission($request)){
            return redirect()->route('user.catalogue.index')->with('success','Cập nhật quyền nhóm thành viên thành công');
        }
        return redirect()->route('user.catalogue.index')->with('error','Cập nhật quyền nhóm thành viên thất bại. Hãy thử lại');
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
            'model'=>'UserCatalogue'
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
            ],
            'css'=>[
                'Backend/vendor/fontawesome-free/css/all.min.css',
                'https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i',
                'Backend/css/sb-admin-2.min.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

}
