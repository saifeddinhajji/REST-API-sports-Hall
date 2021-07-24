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
        Route::delete('/{id}', 'ActivityController@delete');
    });

    Route::group([
        'namespace' => '\App\Http\Controllers',
        'prefix' => 'subscriptions',
    ], function ($router) {
        Route::get('', 'SubscriptionController@index');
        Route::post('', 'SubscriptionController@add');
        Route::put('/{id}', 'SubscriptionController@update');
        Route::delete('/{id}', 'SubscriptionController@delete');
    });



Route::group(['namespace' => '\App\Http\Controllers', 'prefix' => 'coachs'],
    function ($router) {
        Route::get('', 'CoachController@index');
        Route::post('', 'CoachController@add');
        Route::put('/{id}', 'CoachController@update');
        Route::delete('/{id}', 'CoachController@delete');
    });

Route::post('upload_file', 'UploadFileController@uploadFile');

Route::group(['namespace' => '\App\Http\Controllers', 'prefix' => 'gyms'],
    function ($router) {
        Route::get('', 'GymsController@index');
    });

Route::group(['namespace' => '\App\Http\Controllers', 'prefix' => 'managers'],
    function ($router) {
        Route::get('', 'ManagerController@index');
        Route::get('change_status/{id}', 'ManagerController@changeStatus');
    });

Route::group([
        'namespace' => '\App\Http\Controllers',
        'prefix' => 'types_subscriptions',
    ], function ($router) {
        Route::get('', 'TypeSubscriptionController@index');
        Route::post('', 'TypeSubscriptionController@add');
        Route::put('/{id}', 'TypeSubscriptionController@update');
        Route::delete('/{id}', 'TypeSubscriptionController@delete');
    });

    Route::group([
        'namespace' => '\App\Http\Controllers',
        'prefix' => 'offers',
    ], function ($router) {
        Route::get('', 'OfferController@index');
        Route::post('', 'OfferController@add');
        Route::put('/change_status/{id}', 'OfferController@changeStatus');
    });

    Route::group([
        'namespace' => '\App\Http\Controllers',
        'prefix' => 'stats',
    ], function ($router) {
        Route::get('', 'StatsController@index');
    });
