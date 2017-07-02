<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/6/29
 * Time: 20:34
 */

/*
 * 后台管理路由
 * */

Route::group(['prefix'=>'/api/manage'],function (){

    /*管理员部分路由*/
    Route::group(['prefix'=>'/account','namespace'=>'Manage\Account'],function (){
        Route::post('/i/auth','AccountController@login');

        Route::post('/i/info','AccountController@create');


        Route::group(['middleware'=>'auth.account'],function (){
            Route::get('/i/info','AccountController@getMyInfo');

        });
    });
    /*订单部分路由*/
    Route::group(['prefix'=>'/order','namespace'=>'Manage\Order'],function (){


        Route::group(['middleware'=>'auth.account'],function (){
            Route::post('/','OrderController@createOrder'); //管理员创建订单

            Route::put('/status','OrderController@changeOrderStatus'); //管理员更改订单的状态

            Route::get('/','OrderController@getOrderAll');//管理员获取订单 按时间倒序排序
            Route::get('/detail','OrderController@getOrderDetail');//管理员获取订单的详细信息

            Route::get('/statistics','OrderController@getOrderStatistics');//管理员获取订单的统计情况
        });
    });

});