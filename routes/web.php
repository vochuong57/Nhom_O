<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Middleware\LoginMiddleware;
use App\Http\Middleware\AuthenticateMiddleware;
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Backend\UserCatalogueController;
use App\Http\Controllers\Ajax\DashboardController as AjaxDashboardController;

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
Route::group(['prefix'=>'user/catalogue'], function(){
    Route::get('index',[UserCatalogueController::class, 'index'])->name('user.catalogue.index')->middleware(AuthenticateMiddleware::class);
    Route::get('store',[UserCatalogueController::class, 'store'])->name('user.catalogue.store')->middleware(AuthenticateMiddleware::class);
    Route::post('create',[UserCatalogueController::class, 'create'])->name('user.catalogue.create')->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/edit',[UserCatalogueController::class, 'edit'])->name('user.catalogue.edit')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::post('{id}/update',[UserCatalogueController::class, 'update'])->name('user.catalogue.update')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/destroy',[UserCatalogueController::class, 'destroy'])->name('user.catalogue.destroy')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::post('{id}/delete',[UserCatalogueController::class, 'delete'])->name('user.catalogue.delete')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::get('permission',[UserCatalogueController::class, 'permission'])->name('user.catalogue.permission')->where(['id'=>'[0-9]+']);
    Route::post('updatePermission',[UserCatalogueController::class, 'updatePermission'])->name('user.catalogue.updatePermission')->where(['id'=>'[0-9]+']);
});
Route::post('ajax/dashboard/changeStatus',[AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');
