<?php

namespace App\Services;

use App\Services\Interfaces\UserCatalogueServiceInterface;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Models\UserCataloguePermission;

/**
 * Class UserService
 * @package App\Services
 */
class UserCatalogueService implements UserCatalogueServiceInterface
{
    protected $userCatalogueRepository;
    protected $userRepository;

    public function __construct(UserCatalogueRepository $userCatalogueRepository, UserRepository $userRepository){
        $this->userCatalogueRepository=$userCatalogueRepository;
        $this->userRepository=$userRepository;
    }

    public function paginate($request){
        $condition['keyword']=addslashes($request->input('keyword'));
        $perpage=$request->integer('perpage', 20);
        $condition['publish']=$request->input('publish');
        if ($condition['publish'] == '0') {
            $condition['publish'] = null;
        }
        $userCatalogues=$this->userCatalogueRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perpage, 
            ['path'=> 'user/catalogue/index'], 
            [],
            [], 
            ['users']
        );
        return $userCatalogues;
    }
    public function updateStatus($post=[]){
        DB::beginTransaction();
        try{
            $payload[$post['field']]=(($post['value']==1)?2:1);
            $user=$this->userCatalogueRepository->update($post['modelId'], $payload);
            $condition = [
                ['user_catalogue_id', '=', [$post['modelId']]]
            ];
            $this->userRepository->updateByWhere($condition, $payload);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();
            return false;
        }
    }
    private function paginateSelect(){
        return [
            'id','name','description','publish'
        ];
    }
    public function createUserCatalogue($request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');
            $userCatalogue=$this->userCatalogueRepository->create($payload);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }
    public function updateUserCatalogue($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');
            $userCatalogue=$this->userCatalogueRepository->update($id, $payload);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }
    public function deleteUserCatalogue($id){
        DB::beginTransaction();
        try{
            $userCatalogue=$this->userCatalogueRepository->forceDelete($id);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }
}
