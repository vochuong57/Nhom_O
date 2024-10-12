<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\UserInfoRepositoryInterface as UserInfoRepository;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;


class LoginInformationProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('App\Repositories\Interfaces\UserInfoRepositoryInterface','App\Repositories\UserInfoRepository');
        $this->app->bind('App\Repositories\Interfaces\UserCatalogueRepositoryInterface','App\Repositories\UserCatalogueRepository');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('Backend.dashboard.component.nav', function ($view) {
            $id = Auth::id();
            $condition = [
                ['user_id', '=', $id]
            ];
            $userInfoRepository = $this->app->make(UserInfoRepository::class);
            $userInfo = $userInfoRepository->findByCondition($condition);

            $user_catalogueRepository = $this->app->make(UserCatalogueRepository::class);
            // $user_catalogue = $user_catalogueRepository->findById($userInfo->user_catalogue_id);

            $data = [
                'userInfo' => $userInfo,
                // 'user_catalogue' => $user_catalogue,
            ];

            $view->with($data);
        });
    }

}
