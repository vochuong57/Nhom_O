<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\PermissionServiceInterface as PermissionService;
use App\Http\Requests\StorePermissionRequest;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use App\Http\Requests\UpdatePermissionRequest;


class PermissionController extends Controller
{
    protected $permissionService;
    protected $permissionRepository;

    public function __construct(PermissionService $permissionService, PermissionRepository $permissionRepository){
        $this->permissionService=$permissionService;
        $this->permissionRepository=$permissionRepository;
    }
    public function index(Request $request){
        
        $config=$this->configIndex();

        $template='Backend.permission.index';

        $config['seo']=config('apps.permission.index');

        $permissions = $this->permissionService->paginate($request);

        foreach ($permissions as $permission) {
            $permission->encrypted_id = $this->encryptId($permission->id);
        }

        $this->authorize('modules', 'permission.index');

        return view('Backend.dashboard.layout', compact('template','config','permissions'));
    }

    public function store(){   
        $template='Backend.permission.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.permission.create');

        $config['method']='create';

        $this->authorize('modules', 'permission.store');

        return view('Backend.dashboard.layout', compact('template','config'));
    }

    public function create(StorePermissionRequest $request){
        if($this->permissionService->createPermission($request)){
            return redirect()->route('permission.index')->with('success','Thêm mới quyền thành công');
        }
           return redirect()->route('permission.index')->with('error','Thêm mới quyền thất bại. Hãy thử lại');
        
    }
    public function edit($id){
        $template='Backend.permission.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.permission.edit');

        $config['method']='edit';

        $id = $this->decryptId($id);

        if (!preg_match('/^[0-9A-Za-z=]+$/', $id)) {
            return redirect()->route('permission.index')->withErrors('ID không hợp lệ. Vui lòng sử dụng ID đã mã hóa.');
        }

        $permission=$this->permissionRepository->findById($id);

        $this->authorize('modules', 'permission.edit');
       
        return view('Backend.dashboard.layout', compact('template','config','permission'));
    }
    public function update($id, UpdatePermissionRequest $request){
        
        if($this->permissionService->updatePermission($id, $request)){
            return redirect()->route('permission.index')->with('success','Cập nhật quyền thành công');
        }
           return redirect()->route('permission.index')->with('error','Cập nhật quyền thất bại. Hãy thử lại');
    }
    public function destroy($id){
        $template='Backend.permission.destroy';

        $config=$this->configCUD();

        $config['seo']=config('apps.permission.delete');

        $id = $this->decryptId($id);

        if (!preg_match('/^[0-9A-Za-z=]+$/', $id)) {
            return redirect()->route('permission.index')->withErrors('ID không hợp lệ. Vui lòng sử dụng ID đã mã hóa.');
        }

        $permission=$this->permissionRepository->findById($id);

        $this->authorize('modules', 'permission.destroy');

        return view('Backend.dashboard.layout', compact('template','config','permission'));
    }
    public function delete($id){
        if($this->permissionService->deletePermission($id)){
            return redirect()->route('permission.index')->with('success','Xóa quyền thành công');
        }
           return redirect()->route('permission.index')->with('error','Xóa quyền thất bại. Hãy thử lại');
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
            'model'=>'Permission'
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
