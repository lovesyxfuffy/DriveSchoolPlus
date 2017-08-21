<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/6/30
 * Time: 15:51
 */

namespace App\Http\Controllers\Manage\Order;


use App\Constants\OrderConstants;
use App\Constants\PayedConstants;
use App\Http\Controllers\Controller;
use App\Util\DBUtil;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrderList(Request $request)
    {
        $queryClause = $request->input("queryClause");


        $query = DBUtil::table('order')
            ->leftJoin('class', 'order.classId', '=', 'class.id')
            ->leftJoin('field', 'order.fieldId', '=', 'field.id');

        if ($queryClause != null)
            foreach ($queryClause as $key => $value) {
                $query->where($key, $value);
            }

        return $query->get(["order.*", "class.name as className", "field.name as fieldName"]);
    }

    public function getOrderStatistic(Request $request)
    {

        $startDate = date('Y-m-d 0:0:0');
        $endDate = date('Y-m-d 23:59:59');


        $orderResult = DBUtil::select("select count(*) as count,status from `order` where updateTime BETWEEN '$startDate' and '$endDate'  group by status ");


        foreach ($orderResult as $row) {
            $row->statusStr = OrderConstants::getStatusStrByCode($row->status);
        }

        $payedResult = DBUtil::select("select sum(payedAmount) as sum ,way from payed where updateTime BETWEEN '$startDate' and '$endDate' group by way");


        foreach ($payedResult as $row) {
            $row->wayStr = PayedConstants::getPayedWayStrByCode($row->way);
        }

        $response = [];

        $response['orderStatistic'] = $orderResult;
        $response['payedStatistic'] = $payedResult;

        return $response;

    }

}