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
        $condition['user_catalogue_id']=$request->input('user_catalogue_id');
        if($condition['user_catalogue_id']=='0'){
            $condition['user_catalogue_id']=null;
        }
        $perpage=$request->integer('perpage', 20);
        $users=$this->userRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perpage, 
            ['path'=> 'user/index'],
            ['users.user_catalogue_id','ASC'],
            [
                ['user_info as tb2','tb2.user_id','=','users.id']
            ]
        );
        return $users;
    }

}
