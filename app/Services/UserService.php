<?php

namespace App\Services;

use App\Services\Interfaces\UserServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Repositories\Interfaces\UserInfoRepositoryInterface as UserInfoRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


/**
 * Class UserService
 * @package App\Services
 */
class UserService implements UserServiceInterface
{
    protected $userRepository;
    protected $userInfoRepository;

    public function __construct(UserRepository $userRepository, UserInfoRepository $userInfoRepository){
        $this->userRepository=$userRepository;
        $this->userInfoRepository=$userInfoRepository;
    }

    public function paginate($request){
        $condition['keyword']=addslashes($request->input('keyword'));
        $condition['publish']=$request->input('publish');
        $condition['user_catalogue_id']=$request->input('user_catalogue_id');
        if($condition['user_catalogue_id']=='0'){
            $condition['user_catalogue_id']=null;
        }
        if ($condition['publish'] == '0') {
            $condition['publish'] = null;
        }
        $perpage=$request->integer('perpage', 20);
        $users=$this->userRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perpage, 
            ['path'=> 'user/index'],
            ['users.user_catalogue_id','ASC'],
            [
                ['user_info as tb2','tb2.user_id','=','users.id', 'left']
            ]
        );
        return $users;
    }

    public function updateUser($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->only('email','password','user_catalogue_id');
            if(isset($payload['password'])){
                if($payload['password'] == null || $payload['password'] == ''){
                    $payload['password'] = auth()->user()->password;
                }                    
            }else{
                $payload['password'] = auth()->user()->password;
            }
            $flag=$this->userRepository->update($id, $payload);            
            if($flag==true){
                $condition=[
                    ['user_id', '=', $id]
                ];
                $payloadUserInfo = $request->only($this->payloadUserInfo());
                $payloadUserInfo['user_id'] = $id;
                $payloadUserInfo['birthday'] = Carbon::createFromFormat('d/m/Y', $payloadUserInfo['birthday'])->format('Y-m-d');
                if(!empty($this->userInfoRepository->findByCondition($condition))){
                    $effectRows = $this->userInfoRepository->updateByWhere($condition,$payloadUserInfo);
                }else{
                    $effectRows = $this->userInfoRepository->create($payloadUserInfo);
                }
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }
    public function createUser($request){
        DB::beginTransaction();
        try{
            $payload = $request->only('email','password', 'user_catalogue_id', 'publish');
            $payload['password']=Hash::make($payload['password']);
            $user=$this->userRepository->create($payload);
            $payloadUserInfo = $request->only($this->payloadUserInfo());
            $payloadUserInfo['user_id'] = $user->id;
            $payloadUserInfo['birthday'] = Carbon::createFromFormat('d/m/Y', $payloadUserInfo['birthday'])->format('Y-m-d');
            $this->userInfoRepository->create($payloadUserInfo);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }
    public function deleteUser($id){
        DB::beginTransaction();
        try{
            $user=$this->userRepository->forceDelete($id);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }
    public function editUser($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->only('email','password','user_catalogue_id');
            if(isset($payload['password'])){
                if($payload['password'] == null){
                    $payload['password'] = auth()->user()->password;
                }                    
            }else{
                $payload['password'] = auth()->user()->password;
            }
            $flag=$this->userRepository->update($id, $payload);            
            if($flag==true){
                $condition=[
                    ['user_id', '=', $id]
                ];
                $payloadUserInfo = $request->only($this->payloadUserInfo());
                $payloadUserInfo['user_id'] = $id;
                $this->userInfoRepository->updateByWhere($condition,$payloadUserInfo);
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    private function paginateSelect(){
        return [
            'id','email','publish','tb2.phone','tb2.address','tb2.name','tb2.image','users.user_catalogue_id'
        ];
    }

    private function payloadUserInfo(){
        return [
            'name',
            'phone',
            'province_id',
            'district_id',
            'ward_id',
            'address',
            'birthday',
            'image',
            'description',
            'user_catalogue_id',
            'user_id'
        ];
    }
}
