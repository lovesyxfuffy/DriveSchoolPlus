<?php

Route::group(['prefix'=>'/system','namespace'=>'Manage\System'],function (){

    Route::get('/menu/{viewId}','SystemController@getMenu');

    Route::get('/test','SystemController@test');

});