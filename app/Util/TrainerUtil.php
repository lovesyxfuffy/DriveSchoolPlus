<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/7/10
 * Time: 21:25
 */

namespace App\Util;


class TrainerUtil
{
    /*
     * 教练的状态
     * */
    static $statusOnline  = 1;
    static $statusOffline = 2;

    static $CheckRules = [
        'headImg'         =>'required|filled',
        'name'            =>'required|filled|max:30',
        'age'             =>'required|filled|numeric|digits:4',
        'sex'             =>'required|filled',
        'teachYear'       =>'required|filled|numeric|digits:4',
        'idCard'          =>'required|filled|max:18',
        'telephone'       =>'required|filled',
        'carNumber'       =>'required|filled',
        'weixin'          =>'required|filled',
//        'qq'            =>'required|filled|numeric',
//        'fieldId'       =>'required|filled|numeric',
    ];

    /*
    * 处理年龄和驾龄
    * */
    static function dealAgeTeachYear($trainer){
        foreach ($trainer as $row){
            $row->age        = date('Y') - $row->age;
            $row->teach_year = date('Y') - $row->teach_year;
        }
        return $trainer;
    }

}