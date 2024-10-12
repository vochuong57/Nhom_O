<?php
namespace App\Repositories;

use App\Repositories\Interfaces\UserInfoRepositoryInterface;
use App\Models\UserInfo;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class UserInfoRepository extends BaseRepository implements UserInfoRepositoryInterface
{
    protected $model;
    public function __construct(UserInfo $model){
        $this->model=$model;
    }
   
}
