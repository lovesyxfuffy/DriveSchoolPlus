<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/6/29
 * Time: 21:02
 */

namespace App\Util;


class ResponseUtil
{
    static function result($content){
        return response()->json([
            'code' => '200',
            'msg' => $content
        ]);
    }
    static function error($errorMsg){
        return response()->json([
            'code' => '500',
            'msg' => $errorMsg
        ]);
    }
    static function waring($warningMsg){
        return response()->json([
            'code' => '405',
            'msg' => $warningMsg
        ]);
    }

}