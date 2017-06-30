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

    Route::group(['prefix'=>'/account','namespace'=>'Manage\Account'],function (){
        Route::post('/i/auth','AccountController@login');

        Route::post('/i/info','AccountController@create');


        Route::group(['middleware'=>'auth.account'],function (){
            Route::get('/i/info','AccountController@getMyInfo');

        });
    });
});