<?php
/**
 * Created by PhpStorm.
 * User: yujingyang
 * Date: 2017/6/29
 * Time: 下午7:43
 */

namespace app\Util;

use Illuminate\Http\Response;


class ResponseEntity
{
    static function result($content){
        return response()->json([
            'code' => '200',
            'msg' => $content
        ]);
    }
    static function error($errorMsg){
        return response()->json([
            'code' => '400',
            'msg' => $errorMsg
        ]);
    }

}