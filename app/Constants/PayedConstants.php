<?php
/**
 * Created by PhpStorm.
 * User: yujingyang
 * Date: 2017/8/10
 * Time: 下午4:27
 */

namespace App\Constants;


class PayedConstants
{

    /*支付方式*/
    static $payedWayOnline = 1;
    static $payedWayOffline = 2;

    public static function getPayedWayStrByCode($payedWay){
        switch ($payedWay){
            case PayedConstants::$payedWayOnline :
                return '在线支付';
            case PayedConstants::$payedWayOffline :
                return '线下支付';
            default :
                return '未知';
        }

    }
}