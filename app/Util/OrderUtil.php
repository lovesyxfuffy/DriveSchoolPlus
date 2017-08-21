<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/6/30
 * Time: 15:38
 */

namespace App\Util;


class OrderUtil
{
    /*
     * 设置 订单的状态
     * */


    static $CheckRules = [
        'stuName'=>'required|filled|max:30',
        'stuIdCard'=>'required|filled|max:18',
        'stuTelephone'=>'required|filled',
        'stuPermit'=>'required|filled',
        'stuQq'=>'required|filled|numeric',
        'fieldId'=>'required|filled|numeric',
        'classId'=>'required|filled|numeric',
        'type'=>'required|filled',
        'stuCost'=>'required|filled|numeric',
    ];


}