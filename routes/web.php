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


/*
 * 引入后台管理的路由文件
 * */
include_once "routeManage.php";

include_once "System.php";

Route::get('/', function () {
    return view('model');
});

Route::get('view/{model}/{viewName}/{viewId}', function ($model, $viewName, $viewId) {
    $menuInfo = new stdClass();
    $menuInfo->viewId = $viewId;


    return view($model . "." . $viewName, ['menuInfo' => $menuInfo]);
});

