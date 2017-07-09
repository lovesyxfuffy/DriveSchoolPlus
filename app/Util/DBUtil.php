<?php
/**
 * Created by PhpStorm.
 * User: yujingyang
 * Date: 2017/6/30
 * Time: 下午4:08
 */

namespace app\Util;



class DBUtil
{
    static function convert($result){
        //遍历$result 下划线转驼峰
        return $result;
    }

    static function select($table , $accountId){
        //权限验证
        return DB::select($table);

    }
}