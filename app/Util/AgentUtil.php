<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/7/16
 * Time: 17:32
 */

namespace App\Util;


class AgentUtil
{
    static $typeOrdinaryAgent = 1;
    static $typeStudentAgent  = 2;

    static $CheckRules = [
        'name'            =>'required|filled|max:30',
        'age'             =>'required|filled|numeric|digits:4',
        'sex'             =>'required|filled',
        'idCard'          =>'required|filled|max:18',
        'telephone'       =>'required|filled',
        'bankCardNumber'  =>'required|filled',
        'bankInfo'        =>'required|filled',
//        'inviteCode'      =>'required|filled',
//        'reduction'       =>'required|filled',
    ];

}