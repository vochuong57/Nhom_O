<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Middleware\LoginMiddleware;
use App\Http\Middleware\AuthenticateMiddleware;
use App\Http\Controllers\Ajax\LocationController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('dashboard/index',[DashboardController::class, 'index'])->name('dashboard.index')->middleware(AuthenticateMiddleware::class);

Route::get('admin',[AuthController::class, 'index'])->name('auth.admin')->middleware(LoginMiddleware::class);
Route::post('login',[AuthController::class, 'login'])->name('auth.login');
Route::get('logout',[AuthController::class, 'logout'])->name('auth.logout');

Route::group(['prefix'=>'user/profile'], function(){
    Route::get('edit',[DashboardController::class, 'edit'])->name('user.profile.edit')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::post('update',[DashboardController::class, 'update'])->name('user.profile.update')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);
});

Route::get('ajax/location/getLocation',[LocationController::class, 'getLocation'])->name('ajax.location.getLocation')->middleware(AuthenticateMiddleware::class);
