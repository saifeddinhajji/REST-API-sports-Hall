<?php
 use Illuminate\Support\Facades\Route;
    Route::group([
        'namespace' => '\App\Http\Controllers',
        'prefix' => 'auth'
    ], function ($router) {
        Route::post('login', 'AuthController@login');
        Route::post('sign_up', 'AuthController@signUp');
        Route::put('update_password', 'AuthController@updatePassword');
        Route::put('me', 'AuthController@update');
        Route::get('me', 'AuthController@me');
    });
    Route::group([
        'namespace' => '\App\Http\Controllers',
        'prefix' => 'activities',
            ], function ($router) {
        Route::get('', 'ActivityController@index');
        Route::post('', 'ActivityController@add');
        Route::put('/{id}', 'ActivityController@update');
        Route::delete('change_status/{id}', 'ActivityController@changeStatus');
     
    });

    Route::group([
        'namespace' => '\App\Http\Controllers',
        'prefix' => 'subscriptions',
    ], function ($router) {
        Route::get('', 'SubscriptionController@index');
        Route::post('', 'SubscriptionController@add');
        Route::put('/{id}', 'SubscriptionController@update');
        Route::put('/{id}/change_status', 'SubscriptionController@changeStatus');
        Route::get('get_settings', 'SubscriptionController@getSettings');
    });
Route::group(['namespace' => '\App\Http\Controllers', 'prefix' => 'employees'],
    function ($router) {
        Route::get('', 'EmployeesController@index');
        Route::post('', 'EmployeesController@add');
        Route::get('list_coachs','EmployeesController@listCoachs');
        Route::put('{id}', 'EmployeesController@update');
        Route::get('{id}', 'EmployeesController@detail');
    });
Route::post('upload_file', 'UploadFileController@uploadFile');

Route::group(['namespace' => '\App\Http\Controllers', 'prefix' => 'gyms'],
    function ($router) {
        Route::get('', 'GymsController@index');
    });

Route::group(['namespace' => '\App\Http\Controllers', 'prefix' => 'managers'],
    function ($router) {
        Route::get('', 'ManagerController@index');
        Route::put('change_status/{id}', 'ManagerController@changeStatus');
    });
Route::group(['namespace' => '\App\Http\Controllers', 'prefix' => 'manager'],
    function ($router) {
        Route::get('subscriptions', 'RenewalController@list');
        Route::post('subscriptions', 'RenewalController@add');
    });

Route::group(['namespace' => '\App\Http\Controllers', 'prefix' => 'cash_management'],
    function ($router) {
        Route::get('', 'CashManagementAdminController@index');
        Route::put('{id}/change_status', 'CashManagementAdminController@changeStatus');
        Route::get('get_settings','CashManagementAdminController@getSettings');
    });

Route::group([
        'namespace' => '\App\Http\Controllers',
        'prefix' => 'types_subscriptions',
    ], function ($router) {
        Route::get('', 'TypeSubscriptionController@index');
        Route::get('{id}', 'TypeSubscriptionController@find');
       Route::post('', 'TypeSubscriptionController@add');
        Route::put('/{id}', 'TypeSubscriptionController@update');
    });

    Route::group([
        'namespace' => '\App\Http\Controllers',
        'prefix' => 'offers',
    ], function ($router) {
        Route::get('', 'OfferController@index');
        Route::post('', 'OfferController@add');
        Route::put('{id}', 'OfferController@update');
        Route::put('{id}/change_status', 'OfferController@changeStatus');

    });
Route::group([
    'namespace' => '\App\Http\Controllers',
    'prefix' => 'adherents',
], function ($router) {
    Route::get('', 'AdherentController@index');
    Route::post('', 'AdherentController@add');
    Route::put('{id}', 'AdherentController@update');
});

    Route::group([
        'namespace' => '\App\Http\Controllers',
        'prefix' => 'stats',
    ], function ($router) {
        Route::get('admin', 'StatsController@AdminStats');
        Route::get('manager', 'StatsController@ManagerStats');

    });
