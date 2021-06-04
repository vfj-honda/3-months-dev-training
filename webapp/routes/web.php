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

// Route::group(['prefix' => 'admin'], function (){
    
    Auth::routes();
    
    Route::group(['prefix' => '/admin', 'middleware' => 'auth'], function(){

        Route::get('/', 'Admin\HomeController@index')->name('admin.home');

        Route::group(['prefix' => '/employee'], function(){

            Route::get('/create', 'Admin\EmployeeController@create')->name('admin.employee.create');
            Route::post('/create', 'Admin\EmployeeController@store')->name('admin.employee.store');
    
            Route::get('/edit/{id}', 'Admin\EmployeeController@edit')->name('admin.employee.edit');
            Route::put('/update/{id}', 'Admin\EmployeeController@update')->name('admin.employee.update');
    
            Route::get('/list', 'Admin\EmployeeController@show')->name('admin.employee.list');
            Route::delete('/destroy', 'Admin\EmployeeController@destroy')->name('admin.employee.destroy');
    
            Route::get('/orders_list', 'Admin\OrderController@show')->name('admin.employee.order_list');
            Route::put('/orders_list/switch', 'Admin\OrderController@switch')->name('admin.employee.order_list.switch');
            
        });
        
        Route::get('/notification/edit', 'Admin\NotificationController@edit')->name('admin.notification.edit');
        Route::put('/notification/update', 'Admin\NotificationController@update')->name('admin.notification.update');
        Route::get('/notification/logs', 'Admin\NotificationController@logs')->name('admin.notification.logs');

        Route::post('/skip/create', 'Admin\SkipController@create')->name('admin.skip.create');
        Route::delete('/skip/destroy', 'Admin\SkipController@destroy')->name('admin.skip.destroy');
        
        Route::get('/logs', 'Admin\LogController@show')->name('admin.log.list');
    });
    
// });

Route::get('/', 'User\HomeController@root')->name('user.root');
Route::get('/dashboard/{year}/{month}', 'User\HomeController@index')->name('user.home');
