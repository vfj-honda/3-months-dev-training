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


Auth::routes();

Route::group(['prefix' => '/admin', 'middleware' => 'auth'], function(){
    
    Route::get('/', 'Admin\HomeController@index')->name('user.root');

    Route::get('/dashboard/{year}/{month}', 'User\HomeController@index')->name('user.home');

    Route::group(['middleware' => 'auth.error'], function() {

        Route::group(['prefix' => '/employee'], function(){
    
            Route::get('/create', 'Admin\EmployeeController@create')->name('admin.employee.create');
            Route::post('/create', 'Admin\EmployeeController@store')->name('admin.employee.store');
    
            Route::get('/edit/{id}', 'Admin\EmployeeController@edit')->name('admin.employee.edit');
            Route::put('/update/{id}', 'Admin\EmployeeController@update')->name('admin.employee.update');
    
            Route::get('/list', 'Admin\EmployeeController@show')->name('admin.employee.list');
            Route::delete('/destroy', 'Admin\EmployeeController@destroy')->name('admin.employee.destroy');
    
            Route::get('/authority', 'Admin\EmployeeController@authority')->name('admin.employee.authority');
            Route::put('/authority/elevate', 'Admin\EmployeeController@elevate')->name('admin.employee.authority.elevate');
            Route::put('/authority/diselevate', 'Admin\EmployeeController@diselevate')->name('admin.employee.authority.diselevate');
    
            Route::get('/orders_list', 'Admin\OrderController@show')->name('admin.employee.order_list');
            Route::put('/orders_list/switch', 'Admin\OrderController@switch')->name('admin.employee.order_list.switch');
            Route::put('/orders_list/insert', 'Admin\OrderController@insert')->name('admin.employee.order_list.insert');
            
        });
        
    
        Route::get('/notification/edit', 'Admin\NotificationController@edit')->name('admin.notification.edit');
        Route::put('/notification/update', 'Admin\NotificationController@update')->name('admin.notification.update');
        Route::get('/notification/logs', 'Admin\NotificationController@logs')->name('admin.notification.logs');
    
        Route::post('/skip/create', 'Admin\SkipController@create')->name('admin.skip.create');
        Route::delete('/skip/destroy', 'Admin\SkipController@destroy')->name('admin.skip.destroy');

        Route::post('/skip/csv_import', 'Admin\SkipController@import')->name('admin.skips.csv_import');

        Route::post('/fixed_post_date/create', 'Admin\FixedPostDatesController@create')->name('admin.fixed_post_date.create');
        
        Route::get('/logs', 'Admin\LogController@show')->name('admin.log.list');
    });

});


