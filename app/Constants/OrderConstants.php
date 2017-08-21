<?php
/**
 * Created by PhpStorm.
 * User: yujingyang
 * Date: 2017/8/10
 * Time: 下午3:50
 */

namespace App\Constants;


class OrderConstants
{
    static $OrderStatusReady = 1;/*订单待支付*/
    static $OrderStatusCanceled = 2;/*订单取消*/
    static $OrderStatusSucceed = 3;/*成功支付*/
    static $OrderStatusConfirmed = 4;/*订单确认*/

    public static function getStatusStrByCode($status){
        switch ($status){
            case OrderConstants::$OrderStatusReady :
                return '待支付';
            case OrderConstants::$OrderStatusCanceled :
                return '已取消';
            case OrderConstants::$OrderStatusConfirmed :
                return '订单确认';
            case OrderConstants::$OrderStatusSucceed:
                return '订单已支付';
            default :
                return '未知';
        }
    }
}