<?php
namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;
    public function __construct(User $model){
        $this->model=$model;
    }
    public function pagination(
        array $column=['*'],
        array $condition=[],
        int $perpage=0, 
        array $extend=[],
        array $orderBy=['id', 'DESC'],
        array $join=[],
        array $relations=[],
        array $rawQuery = []
    ) {
        $query = $this->model->select($column)->where(function ($query) use ($condition) {
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where(function ($query) use ($condition) {
                    $query->where('name', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('email', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('phone', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('address', 'LIKE', '%' . $condition['keyword'] . '%');
                });
            }

            if (isset($condition['publish'])) {
                $query->where('publish', '=', $condition['publish']);
            }

            if (isset($condition['user_catalogue_id'])) {
                $query->where('tb2.user_catalogue_id', '=', $condition['user_catalogue_id'])
                ->orWhere('users.user_catalogue_id', '=', $condition['user_catalogue_id']);
            }
        })->with('user_catalogues');
        if(isset($relations)&&!empty($relations)){
            foreach($relations as $relation){
                $query->withCount($relation);
            }
        }

        if (isset($join) && is_array($join) && count($join)) {
            foreach ($join as $key => $val) {
                if (isset($val[4]) && $val[4] == 'left') {
                    $query->leftJoin($val[0], $val[1], $val[2], $val[3]);
                } else {
                    $query->join($val[0], $val[1], $val[2], $val[3]);
                }
            }
        }

        if(isset($orderBy)&&is_array($orderBy)&&count($orderBy)){
            $query->orderBy($orderBy[0],$orderBy[1]);
        }
        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }

}
