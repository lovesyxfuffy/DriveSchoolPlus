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

    Route::group(['prefix'=>'order','namespace'=>'Manage\Order'],function(){
        Route::get("getOrderList","OrderController@getOrderList");
        Route::get("getOrderStatistic","OrderController@getOrderStatistic");
    });

});
