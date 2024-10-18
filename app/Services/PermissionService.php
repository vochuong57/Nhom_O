<?php

namespace App\Services;

use App\Services\Interfaces\PermissionServiceInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserService
 * @package App\Services
 */
class PermissionService implements PermissionServiceInterface
{
    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository){
        $this->permissionRepository=$permissionRepository;
    }

    public function paginate($request){
        $condition['keyword']=addslashes($request->input('keyword'));
        $condition['publish']=$request->input('publish');
        if ($condition['publish'] == '0') {
            $condition['publish'] = null;
        }
        $perpage=$request->integer('perpage', 20);
        $permissions=$this->permissionRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'permission/index'],
            ['id','DESC']
        );
        return $permissions;
    }
    public function createPermission($request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');
            $permission=$this->permissionRepository->create($payload);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    public function updatePermission($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');
            $permission=$this->permissionRepository->update($id, $payload);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
   
    public function deletePermission($id){
        DB::beginTransaction();
        try{
            $where=[
                ['id', '=', $id]
            ];
            $payload=['canonical'=>null];
            $this->permissionRepository->updateByWhere($where,$payload);
            $permission=$this->permissionRepository->delete($id);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    
    public function deleteAll($post=[]){
        DB::beginTransaction();
        try{
            $permissions=$this->permissionRepository->deleteByWhereIn('id',$post['id']);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
   
    private function paginateSelect(){
        return[
            'id',
            'name',
            'canonical'
        ];
    }
    
}
