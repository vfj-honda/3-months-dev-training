<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

# ルートにアクセスしたら/{year}/{month}にredirect 

Route::group(['prefix' => 'admin'], function (){
    
    Auth::routes();
    
    Route::group(['middleware' => 'auth'], function(){

        Route::get('/', 'Admin\HomeController@root')->name('admin.home');
        
        Route::get('/employee/create', 'Admin\EmployeeController@create')->name('admin.employee.create');
        Route::get('/employee/list', 'Admin\EmployeeController@show')->name('admin.employee.list');
        
        Route::get('/notification', 'Admin\NotificationController@edit')->name('admin.notification.edit');
        Route::get('/notification/logs', 'Admin\NotificationController@logs')->name('admin.notification.logs');
        
        Route::get('/logs', 'Admin\LogController@show')->name('admin.log.list');
    });
    
});

Route::get('/', 'User\HomeController@root')->name('user.root');
Route::get('/{year}/{month}', 'User\HomeController@index')->name('user.home');
