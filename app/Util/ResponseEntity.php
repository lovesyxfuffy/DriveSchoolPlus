<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/6/29
 * Time: 21:12
 */

namespace App\Util;


class ResponseEntity
{

    /*
     * 定义返回状态码
     * */
    static $statusServerError = 500;

    static $statusBadRequest = 400;
    static $statusAuthFail = 401;
    static $statusForbidden = 403;
    static $statusNotFound = 404;
    static $statusMethodNotAllow = 405;
    static $statusRequestTimeout = 408;


    /*
     * 正确返回结果时候
     * */
    static function result($content){
        return response()->json([
            'code' => '200',
            'msg' => $content
        ]);
    }
    static function error($errorCode,$errorMsg){
        return response()->json([
            'code' => $errorCode,
            'msg' => $errorMsg
        ]);
    }



   /* static function waring($warningMsg){
        return response()->json([
            'code' => '405',
            'msg' => $warningMsg
        ]);
    }*/

}